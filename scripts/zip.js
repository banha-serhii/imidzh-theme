#!/usr/bin/env node
/**
 * Zip dist/imidzh-theme/ as imidzh-theme-x.y.z.zip (cPanel manual upload path).
 *
 * Usage:
 *   npm run zip
 */

import fs from 'node:fs';
import path from 'node:path';
import { spawn } from 'node:child_process';
import archiver from 'archiver';
import { parseArgs } from './lib/cli.js';
import * as log from './lib/log.js';
import { DIST_ROOT, DIST_THEME, THEME_ROOT, THEME_SLUG } from './lib/paths.js';
import { readCurrentVersion } from './lib/version.js';

const HELP = `Create dist/imidzh-theme-x.y.z.zip from the production package.

Usage:
  npm run zip
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

function zipDist(version) {
	const zipName = `${THEME_SLUG}-${version}.zip`;
	const zipPath = path.join(DIST_ROOT, zipName);

	return new Promise((resolve, reject) => {
		fs.mkdirSync(DIST_ROOT, { recursive: true });
		const output = fs.createWriteStream(zipPath);
		const archive = archiver('zip', { zlib: { level: 9 } });

		output.on('close', () => resolve({ zipPath, bytes: archive.pointer() }));
		archive.on('error', reject);
		archive.pipe(output);
		archive.directory(DIST_THEME, THEME_SLUG);
		archive.finalize();
	});
}

async function main() {
	const args = parseArgs();
	if (args.help) {
		process.stdout.write(HELP);
		return;
	}

	await runBuild();
	const version = readCurrentVersion();
	const { zipPath, bytes } = await zipDist(version);
	const kb = Math.max(1, Math.round(bytes / 1024));
	log.ok(`Wrote ${zipPath} (${kb} KB)`);
	log.info('Unzip into wp-content/themes/ so the folder name stays imidzh-theme.');
}

main().catch((err) => {
	log.error(err.message || err);
	process.exit(1);
});
