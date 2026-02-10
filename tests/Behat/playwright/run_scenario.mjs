import { chromium } from 'playwright';

const rawCommands = process.argv[2] ?? '[]';
const commands = JSON.parse(rawCommands);
const baseUrl = process.env.BEHAT_BASE_URL ?? process.env.APP_URL ?? 'http://127.0.0.1';

const browser = await chromium.launch({ headless: true });
const page = await browser.newPage();

const absoluteUrl = (path) => {
    if (path.startsWith('http://') || path.startsWith('https://')) {
        return path;
    }

    return new URL(path, baseUrl).toString();
};

const locateField = (field) => page.getByLabel(field, { exact: true });

try {
    for (const command of commands) {
        if (command.type === 'open') {
            await page.goto(absoluteUrl(command.path), { waitUntil: 'domcontentloaded' });
            continue;
        }

        if (command.type === 'fill') {
            await locateField(command.field).fill(command.value);
            continue;
        }

        if (command.type === 'click') {
            await page.getByText(command.text, { exact: true }).first().click();
            continue;
        }

        if (command.type === 'assert_path') {
            const currentPath = new URL(page.url()).pathname;

            if (currentPath !== command.path) {
                throw new Error(`Expected path "${command.path}" but found "${currentPath}".`);
            }

            continue;
        }

        if (command.type === 'assert_text') {
            const isVisible = await page.getByText(command.text, { exact: true }).first().isVisible();

            if (!isVisible) {
                throw new Error(`Expected to see text "${command.text}" but it was not visible.`);
            }

            continue;
        }

        if (command.type === 'assert_field_value') {
            const value = await locateField(command.field).inputValue();

            if (value !== command.value) {
                throw new Error(`Expected field "${command.field}" value "${command.value}" but found "${value}".`);
            }

            continue;
        }

        throw new Error(`Unsupported command type "${command.type}".`);
    }
} finally {
    await browser.close();
}
