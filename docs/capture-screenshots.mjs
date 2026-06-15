import { chromium } from 'playwright';
import { mkdir } from 'fs/promises';
import path from 'path';
import { fileURLToPath } from 'url';

const __dirname = path.dirname(fileURLToPath(import.meta.url));
const outDir = path.join(__dirname, 'screenshots');
const baseUrl = 'http://localhost:8000';

async function screenshot(page, name) {
  const filePath = path.join(outDir, `${name}.png`);
  await page.screenshot({ path: filePath, fullPage: true });
  console.log(`Saved: ${filePath}`);
}

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
  // 1. Strona główna
  await page.goto(baseUrl, { waitUntil: 'domcontentloaded' });
  await screenshot(page, '01-strona-glowna');

  // 2. Logowanie
  await page.goto(`${baseUrl}/login`, { waitUntil: 'domcontentloaded' });
  await screenshot(page, '02-logowanie');

  // 3. Rezerwacja wizyty (pacjent)
  await login(page, 'pacjent1@test.pl', 'password');
  await page.goto(`${baseUrl}/doctors/1`, { waitUntil: 'domcontentloaded' });
  await page.waitForURL(/\/doctors\/1/);

  await page.selectOption('#service_id', { index: 1 });
  await page.waitForTimeout(500);

  const visibleSlot = page.locator('.slot-option[data-available-for-service="1"]').first();
  await visibleSlot.waitFor({ state: 'visible' });
  await visibleSlot.click();
  await page.waitForTimeout(300);
  await screenshot(page, '03-rezerwacja-wizyty');

  await Promise.all([
    page.waitForURL(/\/appointments\/\d+\/payment/, { timeout: 60000 }),
    page.click('#bookingForm button[type="submit"]'),
  ]);

  // 4. Płatność
  await page.waitForLoadState('domcontentloaded');
  await page.locator('input[name="payment_method"][value="blik"]').click();
  await page.fill('#blik_code', '123456');
  await screenshot(page, '04-platnosc');

  await Promise.all([
    page.waitForURL(/\/my-appointments/, { timeout: 60000 }),
    page.click('#paymentForm button[type="submit"]'),
  ]);

  const paymentUrl = page.url();
  const appointmentIdMatch = paymentUrl.match(/appointments\/(\d+)/);
  let appointmentId = appointmentIdMatch?.[1];

  if (!appointmentId) {
    const reviewLink = page.locator('a[href*="/review"]').first();
    const href = await reviewLink.getAttribute('href').catch(() => null);
    appointmentId = href?.match(/appointments\/(\d+)/)?.[1];
  }

  // 5. Potwierdzenie przez lekarza
  await context.clearCookies();
  await login(page, 'doktor1@test.pl', 'password');
  await page.goto(`${baseUrl}/doctor/appointments`, { waitUntil: 'domcontentloaded' });
  await screenshot(page, '05-potwierdzenie-lekarza');

  const confirmButton = page.locator('button:has-text("Potwierdź")').first();
  if (await confirmButton.isVisible()) {
    await confirmButton.click();
    await page.waitForLoadState('domcontentloaded');
  }

  const completeButton = page.locator('button:has-text("Zakończ")').first();
  if (await completeButton.isVisible()) {
    await completeButton.click();
    await page.waitForLoadState('domcontentloaded');
  }

  // 6. Wystawienie opinii
  await context.clearCookies();
  await login(page, 'pacjent1@test.pl', 'password');

  if (!appointmentId) {
    const payLink = page.locator('a[href*="/appointments/"]').first();
    const href = await payLink.getAttribute('href');
    appointmentId = href?.match(/appointments\/(\d+)/)?.[1];
  }

  if (appointmentId) {
    await page.goto(`${baseUrl}/appointments/${appointmentId}/review`, {
      waitUntil: 'domcontentloaded',
    });
  } else {
    await page.goto(`${baseUrl}/my-appointments`, { waitUntil: 'domcontentloaded' });
    const reviewLink = page.locator('a:has-text("Dodaj opinię")').first();
    await reviewLink.click();
    await page.waitForLoadState('domcontentloaded');
  }

  await page.selectOption('#rating', '5');
  await page.fill('#comment', 'Bardzo profesjonalna obsługa i szybka rezerwacja terminu.');
  await screenshot(page, '06-opinia');
} catch (error) {
  console.error('Screenshot error:', error.message);
  process.exitCode = 1;
} finally {
  await browser.close();
}
