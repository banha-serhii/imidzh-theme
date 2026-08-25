/**
 * Small TTY-aware logger. Never prints secrets.
 */

const useColor = Boolean(process.stdout.isTTY) && !process.env.NO_COLOR;

const ansi = {
	reset: '\u001b[0m',
	dim: '\u001b[2m',
	cyan: '\u001b[36m',
	green: '\u001b[32m',
	yellow: '\u001b[33m',
	red: '\u001b[31m',
	bold: '\u001b[1m',
};

function paint(color, text) {
	if (!useColor) {
		return text;
	}
	return `${ansi[color]}${text}${ansi.reset}`;
}

function prefix() {
	return paint('dim', '[imidzh]');
}

export function info(message) {
	console.log(`${prefix()} ${message}`);
}

export function ok(message) {
	console.log(`${prefix()} ${paint('green', message)}`);
}

export function warn(message) {
	console.warn(`${prefix()} ${paint('yellow', message)}`);
}

export function error(message) {
	console.error(`${prefix()} ${paint('red', message)}`);
}

export function title(message) {
	console.log(`${prefix()} ${paint('bold', message)}`);
}

export function dim(message) {
	console.log(`${prefix()} ${paint('dim', message)}`);
}
