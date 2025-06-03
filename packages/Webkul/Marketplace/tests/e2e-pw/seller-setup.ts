import { test as base, expect } from "./setup";
import { type Page } from "@playwright/test";
import * as fs from 'fs';


export async function sellerLogin(page: Page) {
    // Read credentials
    const credentials = JSON.parse(fs.readFileSync('credentials.json', 'utf-8'));

    await page.goto("http://192.168.15.80/Bagisto-v222/mp-v/public/seller/login");

    await page.fill('//input[@placeholder="email@example.com"]', credentials.email);
    await page.fill('//input[@placeholder="Password"]', credentials.password);
    await page.click('//button[contains(.," Sign In ")]');

}

export { expect };