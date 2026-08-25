#!/usr/bin/env node
/**
 * Build (optional version bump) and upload dist/imidzh-theme/ via SFTP or FTPS.
 *
 * Usage:
 *   npm run deploy
 *   npm run deploy -- --dry-run
 *   npm run deploy -- --no-bump
 *   npm run deploy -- --level minor
 *   npm run deploy -- --version 1.2.0
 *   npm run deploy -- --delete
 */

import { spawn } from 'node:child_process';
import path from 'node:path';
import { parseArgs } from './lib/cli.js';
import { uploadFtps } from './lib/deploy-ftps.js';
import { uploadSftp } from './lib/deploy-sftp.js';
import { loadDeployConfig, publicDeployConfig } from './lib/env.js';
import * as log from './lib/log.js';
import { DIST_THEME, THEME_ROOT } from './lib/paths.js';
import { bumpVersion, readCurrentVersion, writeVersion } from './lib/version.js';

const HELP = `Build and upload dist/imidzh-theme/ to shared hosting.

Usage:
  npm run deploy
  npm run deploy -- --dry-run
  npm run deploy -- --no-bump
  npm run deploy -- --level minor|major
  npm run deploy -- --version x.y.z
  npm run deploy -- --delete

Default: bump patch, then build, then upload.
--delete removes remote files that are not in dist (OFF by default).
`;

function runBuild() {
	return new Promise((resolve, reject) => {
		const child = spawn(process.execPath, [path.join(THEME_ROOT, 'scripts/build.js')], {
			cwd: THEME_ROOT,
			stdio: 'inherit',
		});
		child.on('error', reject);
		child.on('close', (code) => {
			if (code === 0) {
				resolve();
			} else {
				reject(new Error(`build failed with exit code ${code}`));
			}
		});
	});
}

function printChecklist(version) {
	log.title('Post-deploy checklist');
	log.info('  [ ] Purge page cache / CDN / host cache (if any)');
	log.info('  [ ] Hard refresh the site (Cmd+Shift+R / Ctrl+Shift+R)');
	log.info(`  [ ] View page source: style.css?ver=${version} matches IMIDZH_VERSION`);
	log.info('  [ ] Spot-check mega-menu, search, and self-hosted fonts');
}

async function main() {
	const args = parseArgs();
	if (args.help) {
		process.stdout.write(HELP);
		return;
	}

	const config = loadDeployConfig();
	const publicCfg = publicDeployConfig(config);
	const current = readCurrentVersion();

	let version = current;
	if (args.dryRun) {
		if (args.version) {
			version = args.version;
		} else if (!args.noBump) {
			version = bumpVersion(current, args.level || 'patch');
		}
		log.title(`Dry-run deploy imidzh-theme ${version}`);
		log.info(`Current source version: ${current} (not written)`);
		if (!args.noBump && version !== current) {
			log.info(`Would bump: ${current} → ${version}`);
		}
	} else if (args.version) {
		writeVersion(args.version);
		version = args.version;
		log.ok(`Version set to ${version}`);
	} else if (!args.noBump) {
		const next = bumpVersion(current, args.level || 'patch');
		writeVersion(next);
		version = next;
		log.ok(`Version: ${current} → ${version}`);
	} else {
		log.info(`Version unchanged: ${version} (--no-bump)`);
	}

	log.info(`Protocol: ${publicCfg.protocol.toUpperCase()}`);
	log.info(`Target:   ${publicCfg.user}@${publicCfg.host}:${publicCfg.port}`);
	log.info(`Remote:   ${publicCfg.remotePath}`);
	log.info(`Auth:     ${publicCfg.auth}`);
	if (args.delete) {
		log.warn('Remote orphan delete is ON (--delete)');
	}

	if (args.dryRun) {
		log.info('Building current sources for the file list (version bump skipped).');
	}

	await runBuild();

	const uploadOpts = { dryRun: args.dryRun, deleteOrphans: args.delete };
	const result =
		config.protocol === 'ftps'
			? await uploadFtps(config, DIST_THEME, uploadOpts)
			: await uploadSftp(config, DIST_THEME, uploadOpts);

	if (args.dryRun) {
		log.ok(`Dry-run complete: ${result.uploaded} files would be uploaded`);
		log.info(`Version that would ship: ${version}`);
		log.info(`Remote path: ${config.remotePath}`);
		printChecklist(version);
		return;
	}

	log.ok(`Uploaded ${result.uploaded} files (theme ${version})`);
	if (result.deleted) {
		log.warn(`Deleted ${result.deleted} remote orphans`);
	}
	log.info(`Remote path: ${config.remotePath}`);
	printChecklist(version);
}

main().catch((err) => {
	log.error(err.message || err);
	process.exit(1);
});
