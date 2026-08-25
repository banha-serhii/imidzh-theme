/**
 * SFTP upload of dist/imidzh-theme → DEPLOY_REMOTE_PATH.
 */

import path from 'node:path';
import SftpClient from 'ssh2-sftp-client';
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
export async function uploadSftp(config, localRoot, options = {}) {
	const { dryRun = false, deleteOrphans = false } = options;
	const files = await listFiles(localRoot);
	const sftp = new SftpClient();

	const connectOpts = {
		host: config.host,
		port: config.port,
		username: config.user,
		readyTimeout: 20000,
	};
	if (config.privateKey) {
		connectOpts.privateKey = config.privateKey;
		if (config.passphrase) {
			connectOpts.passphrase = config.passphrase;
		}
	} else {
		connectOpts.password = config.password;
	}

	try {
		if (dryRun) {
			log.info(`Would connect SFTP ${config.user}@${config.host}:${config.port}`);
			log.info(`Would ensure remote directory ${config.remotePath}`);
			files.forEach((rel, index) => {
				log.dim(`  [${index + 1}/${files.length}] WOULD upload ${rel}`);
			});
			if (deleteOrphans) {
				log.warn('Would delete remote files not present in dist (--delete).');
			}
			return { uploaded: files.length, deleted: 0, files };
		}

		await sftp.connect(connectOpts);
		await sftp.mkdir(config.remotePath, true);

		let uploaded = 0;
		for (const rel of files) {
			const local = path.join(localRoot, rel);
			const remote = remotePathJoin(config.remotePath, rel);
			const remoteDir = path.posix.dirname(remote);
			await sftp.mkdir(remoteDir, true);
			await sftp.fastPut(local, remote);
			uploaded += 1;
			log.dim(`  [${uploaded}/${files.length}] ${rel}`);
		}

		let deleted = 0;
		if (deleteOrphans) {
			deleted = await deleteRemoteOrphans(sftp, config.remotePath, files);
		}

		return { uploaded, deleted, files };
	} finally {
		try {
			await sftp.end();
		} catch {
			// Connection never opened or already closed.
		}
	}
}

/**
 * @param {SftpClient} sftp
 * @param {string} remoteRoot
 * @param {string[]} localFiles
 */
async function deleteRemoteOrphans(sftp, remoteRoot, localFiles) {
	const localSet = new Set(localFiles);
	const remoteFiles = await listRemoteFiles(sftp, remoteRoot, '');
	let deleted = 0;

	for (const rel of remoteFiles) {
		if (localSet.has(rel)) {
			continue;
		}
		await sftp.delete(remotePathJoin(remoteRoot, rel));
		deleted += 1;
		log.warn(`  deleted orphan ${rel}`);
	}

	return deleted;
}

async function listRemoteFiles(sftp, remoteRoot, relDir) {
	const dir = relDir ? remotePathJoin(remoteRoot, relDir) : remoteRoot;
	const out = [];
	let listing;
	try {
		listing = await sftp.list(dir);
	} catch {
		return out;
	}

	for (const item of listing) {
		if (item.name === '.' || item.name === '..') {
			continue;
		}
		const childRel = relDir ? `${relDir}/${item.name}` : item.name;
		if (item.type === 'd') {
			out.push(...(await listRemoteFiles(sftp, remoteRoot, childRel)));
		} else {
			out.push(childRel);
		}
	}

	return out;
}
