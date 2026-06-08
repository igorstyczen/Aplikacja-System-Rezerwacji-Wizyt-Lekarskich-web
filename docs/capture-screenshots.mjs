import { chromium } from 'playwright';
import { mkdir } from 'fs/promises';
import path from 'path';
import { fileURLToPath } from 'url';

const __dirname = path.dirname(fileURLToPath(import.meta.url));
const outDir = path.join(__dirname, 'screenshots');
const baseUrl = 'http://localhost:8000';

async function login(page, email, password) {
  await page.goto(`${baseUrl}/login`, { waitUntil: 'domcontentloaded' });
  await page.fill('input[name="email"]', email);
  await page.fill('input[name="password"]', password);
  await Promise.all([
    page.waitForURL((url) => !url.pathname.endsWith('/login'), { timeout: 60000 }),
    page.click('button[type="submit"]'),
  ]);
  await page.waitForLoadState('domcontentloaded');
}

async function screenshot(page, name) {
  const filePath = path.join(outDir, `${name}.png`);
  await page.screenshot({ path: filePath, fullPage: true });
  console.log(`Saved: ${filePath}`);
}

await mkdir(outDir, { recursive: true });

const browser = await chromium.launch();
const context = await browser.newContext({
  viewport: { width: 1440, height: 900 },
  locale: 'pl-PL',
});
const page = await context.newPage();
page.setDefaultTimeout(60000);
page.setDefaultNavigationTimeout(60000);

try {
  await page.goto(baseUrl, { waitUntil: 'domcontentloaded' });
  await page.waitForLoadState('domcontentloaded');
  await screenshot(page, '01-strona-glowna');

  await page.goto(`${baseUrl}/login`);
  await page.waitForLoadState('domcontentloaded');
  await screenshot(page, '02-logowanie');

  await page.goto(`${baseUrl}/register`);
  await page.waitForLoadState('domcontentloaded');
  await screenshot(page, '03-rejestracja');

  await page.goto(`${baseUrl}/nfz-comparison`);
  await page.waitForLoadState('domcontentloaded');
  await screenshot(page, '04-porownanie-nfz');

  await page.goto(`${baseUrl}/doctors/1`);
  await page.waitForLoadState('domcontentloaded');
  await screenshot(page, '05-profil-lekarza');

  await login(page, 'pacjent1@test.pl', 'password');
  await page.goto(`${baseUrl}/my-appointments`);
  await page.waitForLoadState('domcontentloaded');
  await screenshot(page, '06-panel-pacjenta');

  await context.clearCookies();
  await login(page, 'doktor1@test.pl', 'password');
  await page.goto(`${baseUrl}/doctor/schedule`);
  await page.waitForLoadState('domcontentloaded');
  await screenshot(page, '07-grafik-lekarza');

  await page.goto(`${baseUrl}/doctor/appointments`);
  await page.waitForLoadState('domcontentloaded');
  await screenshot(page, '08-wizyty-lekarza');

  await context.clearCookies();
  await login(page, 'admin@test.pl', 'password');
  await page.goto(`${baseUrl}/admin/dashboard`);
  await page.waitForLoadState('domcontentloaded');
  await screenshot(page, '09-panel-admina');

  await page.goto(`${baseUrl}/admin/doctors`);
  await page.waitForLoadState('domcontentloaded');
  await screenshot(page, '10-zarzadzanie-lekarzami');
} catch (error) {
  console.error('Screenshot error:', error.message);
  process.exitCode = 1;
} finally {
  await browser.close();
}
