#!/usr/bin/env node
/**
 * Bump theme version across package.json, style.css, and functions.php.
 *
 * Usage:
 *   node scripts/bump.js
 *   node scripts/bump.js --level minor
 *   node scripts/bump.js --version 1.2.0
 */

import { parseArgs } from './lib/cli.js';
import * as log from './lib/log.js';
import { bumpVersion, readCurrentVersion, writeVersion } from './lib/version.js';

const HELP = `Bump theme version (package.json, style.css, IMIDZH_VERSION).

Usage:
  npm run bump -- [--level patch|minor|major]
  npm run bump -- --version x.y.z

Default level is patch.
`;

try {
	const args = parseArgs();
	if (args.help) {
		process.stdout.write(HELP);
		process.exit(0);
	}

	const current = readCurrentVersion();
	const next = args.version || bumpVersion(current, args.level || 'patch');
	writeVersion(next);

	log.ok(`Version: ${current} → ${next}`);
} catch (err) {
	log.error(err.message || err);
	process.exit(1);
}
