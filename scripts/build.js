#!/usr/bin/env node
/**
 * Sync versions and produce dist/imidzh-theme/ (minified CSS/JS, cleaned tree).
 * Never minifies source files in the git working tree.
 *
 * Usage:
 *   npm run build
 *   npm run build -- --version 1.1.1
 */

import { copyThemeToDist } from './lib/copy-theme.js';
import { parseArgs } from './lib/cli.js';
import { listFiles } from './lib/list-files.js';
import * as log from './lib/log.js';
import { minifyDistAssets } from './lib/minify.js';
import { DIST_THEME } from './lib/paths.js';
import { syncVersion } from './lib/version.js';

const HELP = `Build an optimized theme package into dist/imidzh-theme/.

Usage:
  npm run build
  npm run build -- --version x.y.z

Source CSS/JS stay readable. Minification happens only in dist/.
`;

async function main() {
	const args = parseArgs();
	if (args.help) {
		process.stdout.write(HELP);
		return;
	}

	const version = syncVersion(args.version);
	log.title(`Building imidzh-theme ${version}`);

	log.info('Copying theme → dist/imidzh-theme/');
	await copyThemeToDist();

	log.info('Minifying CSS/JS in dist (sources unchanged)');
	const minified = await minifyDistAssets(DIST_THEME);

	const files = await listFiles(DIST_THEME);
	const fonts = files.filter((rel) => rel.endsWith('.woff2')).length;

	log.ok(`dist/imidzh-theme/ ready (${files.length} files)`);
	log.info(`  version:     ${version}`);
	log.info(`  css minified: ${minified.css.length}`);
	log.info(`  js minified:  ${minified.js.length}`);
	log.info(`  fonts woff2:  ${fonts}`);
	log.info(`  output:       ${DIST_THEME}`);
}

main().catch((err) => {
	log.error(err.message || err);
	if (err.stack) {
		log.dim(err.stack);
	}
	process.exit(1);
});
