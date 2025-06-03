import { type Page, expect } from "@playwright/test";
import * as fs from 'fs';


export async function customerLogin(page: Page) {
    // Read credentials
    const credentials = JSON.parse(fs.readFileSync('customer-credentials.json', 'utf-8'));

    await page.goto("http://192.168.15.80/Bagisto-v222/mp-v/public/customer/login");

    await page.fill('//input[@placeholder="email@example.com"]', credentials.email);
    await page.fill('//input[@placeholder="Password"]', credentials.password);
    await page.click('//button[contains(.," Sign In ")]');

}

export { expect };