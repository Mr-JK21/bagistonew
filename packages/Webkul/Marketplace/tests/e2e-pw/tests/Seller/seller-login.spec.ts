import { test, expect } from '@playwright/test';
import { sellerLogin } from "../../seller-setup";

test('Seller Login', async ({ page }) => {
    await sellerLogin(page);

    await expect(page.locator('//h2[contains(.," Profile Score ")]')).toBeVisible();
});