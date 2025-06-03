import { test, expect } from "playwright/test";
import * as fs from 'fs';

test('Customer can Create account', async ({ page }) => {
    
    const randomID = Date.now();

    const customerCredentials = {
        first_name: "customer" + randomID,
        last_name: randomID,
        email: "customer" + randomID + "@example.com",
        password: "admin123",
        confirm_password: "admin123",
    };

    /**
     * Navigate to Shop Front page
     */
    await page.goto("http://192.168.15.80/Bagisto-v222/mp-v/public");
    await page.click('//span[@aria-label="Profile"]');
    await page.click('(//a[contains(.," Sign Up ")])[1]');

    await page.waitForSelector('//h1[contains(.," Become User ")]');

    /**
     * Fill Customer registration form
     */
    await page.fill('//input[@placeholder="First Name"]', customerCredentials.first_name);
    await page.fill('//input[@placeholder="Last Name"]', customerCredentials.last_name.toString());
    await page.fill('//input[@placeholder="email@example.com"]', customerCredentials.email);
    await page.fill('//input[@placeholder="Password"]', customerCredentials.password);
    await page.fill('//input[@placeholder="Confirm Password"]', customerCredentials.confirm_password);

    /**
     * Click on register button
     */ 
    await page.click('//button[contains(.," Register ")]');
    await expect(page).toHaveURL("http://192.168.15.80/Bagisto-v222/mp-v/public/customer/login");


    // Save credentials to a file
    fs.writeFileSync('customer-credentials.json', JSON.stringify(customerCredentials));

});
