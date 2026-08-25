/**
 * FTPS upload of dist/imidzh-theme → DEPLOY_REMOTE_PATH.
 */

import path from 'node:path';
import * as ftp from 'basic-ftp';
import { listFiles } from './list-files.js';
import * as log from './log.js';

function remotePathJoin(root, rel = '') {
	const combined = rel ? `${root.replace(/\/+$/, '')}/${rel.replace(/^\/+/, '')}` : root;
	return combined.replace(/\\/g, '/').replace(/\/{2,}/g, '/');
}

/**
 * @param {object} config
 * @param {string} localRoot
 * @param {{ dryRun?: boolean, deleteOrphans?: boolean }} options
 */
export async function uploadFtps(config, localRoot, options = {}) {
	const { dryRun = false, deleteOrphans = false } = options;
	const files = await listFiles(localRoot);
	const client = new ftp.Client(30000);
	client.ftp.verbose = false;

	if (dryRun) {
		log.info(`Would connect FTPS ${config.user}@${config.host}:${config.port}`);
		log.info(`Would ensure remote directory ${config.remotePath}`);
		files.forEach((rel, index) => {
			log.dim(`  [${index + 1}/${files.length}] WOULD upload ${rel}`);
		});
		if (deleteOrphans) {
			log.warn('Would delete remote files not present in dist (--delete).');
		}
		return { uploaded: files.length, deleted: 0, files };
	}

	try {
		await client.access({
			host: config.host,
			port: config.port,
			user: config.user,
			password: config.password,
			secure: true,
			secureOptions: {
				rejectUnauthorized: config.tlsRejectUnauthorized,
			},
		});

		await client.ensureDir(config.remotePath);

		let uploaded = 0;
		for (const rel of files) {
			const local = path.join(localRoot, rel);
			const dir = path.posix.dirname(rel);
			const targetDir = dir && dir !== '.' ? remotePathJoin(config.remotePath, dir) : config.remotePath;
			// ensureDir changes cwd to the target directory.
			await client.ensureDir(targetDir);
			await client.uploadFrom(local, path.posix.basename(rel));
			uploaded += 1;
			log.dim(`  [${uploaded}/${files.length}] ${rel}`);
		}

		let deleted = 0;
		if (deleteOrphans) {
			deleted = await deleteFtpsOrphans(client, config.remotePath, files);
		}

		return { uploaded, deleted, files };
	} finally {
		client.close();
	}
}

async function deleteFtpsOrphans(client, remoteRoot, localFiles) {
	const localSet = new Set(localFiles);
	await client.cd(remoteRoot);
	const remoteFiles = await listFtpsFiles(client, remoteRoot, '');
	let deleted = 0;

	for (const rel of remoteFiles) {
		if (localSet.has(rel)) {
			continue;
		}
		await client.remove(remotePathJoin(remoteRoot, rel));
		deleted += 1;
		log.warn(`  deleted orphan ${rel}`);
	}

	return deleted;
}

async function listFtpsFiles(client, remoteRoot, relDir) {
	const dir = relDir ? remotePathJoin(remoteRoot, relDir) : remoteRoot;
	const out = [];
	let listing;
	try {
		listing = await client.list(dir);
	} catch {
		return out;
	}

	for (const item of listing) {
		if (item.name === '.' || item.name === '..') {
			continue;
		}
		const childRel = relDir ? `${relDir}/${item.name}` : item.name;
		if (item.isDirectory) {
			out.push(...(await listFtpsFiles(client, remoteRoot, childRel)));
		} else {
			out.push(childRel);
		}
	}

	return out;
}
