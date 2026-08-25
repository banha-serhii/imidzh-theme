/**
 * Copy the theme into dist/imidzh-theme/ without mutating sources.
 * Copies from each top-level child so dist/ is never copied into itself.
 */

import fs from 'node:fs/promises';
import path from 'node:path';
import { DIST_THEME, THEME_ROOT, shouldExclude, toPosix } from './paths.js';

export async function copyThemeToDist() {
	await fs.rm(DIST_THEME, { recursive: true, force: true });
	await fs.mkdir(DIST_THEME, { recursive: true });

	const entries = await fs.readdir(THEME_ROOT, { withFileTypes: true });

	for (const entry of entries) {
		if (shouldExclude(entry.name)) {
			continue;
		}

		const src = path.join(THEME_ROOT, entry.name);
		const dest = path.join(DIST_THEME, entry.name);

		await fs.cp(src, dest, {
			recursive: true,
			filter: (current) => {
				const inner = path.relative(src, current);
				const rel = inner ? `${entry.name}/${toPosix(inner)}` : entry.name;
				return !shouldExclude(rel);
			},
		});
	}
}
