/**
 * Shared argv parser for bump / build / deploy / zip.
 */

const LEVELS = new Set(['patch', 'minor', 'major']);

/**
 * @param {string[]} argv
 * @returns {{
 *   dryRun: boolean,
 *   noBump: boolean,
 *   delete: boolean,
 *   version: string | null,
 *   level: 'patch' | 'minor' | 'major' | null,
 *   help: boolean,
 * }}
 */
export function parseArgs(argv = process.argv.slice(2)) {
	const out = {
		dryRun: false,
		noBump: false,
		delete: false,
		version: null,
		level: null,
		help: false,
	};

	for (let i = 0; i < argv.length; i += 1) {
		const arg = argv[i];

		if (arg === '--dry-run') {
			out.dryRun = true;
			continue;
		}
		if (arg === '--no-bump') {
			out.noBump = true;
			continue;
		}
		if (arg === '--delete') {
			out.delete = true;
			continue;
		}
		if (arg === '--help' || arg === '-h') {
			out.help = true;
			continue;
		}

		if (arg === '--version' || arg === '--level') {
			const value = argv[i + 1];
			if (!value || value.startsWith('--')) {
				throw new Error(`${arg} requires a value`);
			}
			i += 1;
			if (arg === '--version') {
				out.version = value;
			} else {
				out.level = value;
			}
			continue;
		}

		if (arg.startsWith('--version=')) {
			out.version = arg.slice('--version='.length);
			continue;
		}
		if (arg.startsWith('--level=')) {
			out.level = arg.slice('--level='.length);
			continue;
		}

		throw new Error(`Unknown argument: ${arg}\nRun with --help for usage.`);
	}

	if (out.level && !LEVELS.has(out.level)) {
		throw new Error(`Invalid --level "${out.level}". Use patch, minor, or major.`);
	}

	return out;
}
