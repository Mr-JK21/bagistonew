import { test, expect } from "../../setup";
import * as fs from 'fs';

test('Seller can Create account', async ({ adminPage }) => {
    
    const randomID = Date.now();

    const sellerCredentials = {
        name: "seller" + randomID,
        url: "test"+ randomID,
        email: "seller" + randomID + "@example.com",
        password: "admin123",
        confirm_password: "admin123",
    };

    /**
     * Navigate to Shop Front page
     */
    await adminPage.goto("http://192.168.15.80/Bagisto-v222/mp-v/public");
    await adminPage.click('(//a[@aria-label="Sell"])[1]');
    await adminPage.click('//a[@class="primary-button flex items-center gap-2.5"]');

    await adminPage.waitForSelector('//h1[contains(.," Become Seller ")]');

    /**
     * Fill Seller registration form
     */
    await adminPage.fill('//input[@placeholder="Name"]', sellerCredentials.email);
    await adminPage.fill('//input[@placeholder="Shop Url"]', sellerCredentials.url);
    await adminPage.fill('//input[@placeholder="email@example.com"]', sellerCredentials.email);
    await adminPage.fill('//input[@placeholder="Password"]', sellerCredentials.password);
    await adminPage.fill('//input[@placeholder="Confirm Password"]', sellerCredentials.confirm_password);

    /**
     * Click on register button
     */ 
    await adminPage.click('//button[contains(.," Register ")]');
    await adminPage.waitForURL("http://192.168.15.80/Bagisto-v222/mp-v/public/seller/login");

    /**
     * Fill the Login Form
     */
    await adminPage.fill('//input[@placeholder="email@example.com"]', sellerCredentials.email);
    await adminPage.fill('//input[@placeholder="Password"]', sellerCredentials.password);
    await adminPage.click('//button[contains(.," Sign In ")]');

    await adminPage.locator('div.fixed.top-5 ').getByText('Your activation seeks admin approval').isVisible();

    /**
     * Go to the Admin page
     */
    await adminPage.goto('http://192.168.15.80/Bagisto-v222/mp-v/public/admin/marketplace/sellers');

    /**
     *Select the first checkbox in the Seller list
     */
    await adminPage.click('(//label[@class="icon-uncheckbox peer-checked:icon-checked cursor-pointer rounded-md text-2xl peer-checked:text-blue-600"])[1]')

    /**
     *Click on the Select Action dropdown
     */
    await adminPage.click('//button[contains(., "Select Action") ]')

    /**
     *Hover over the Update Status option
     */
    const updateStatus = await adminPage.locator("xpath=//span[contains(.,'Update Status')]");
    await updateStatus.hover();

    /**
     *Click on the second dropdown option
     */
    await adminPage.click("//a[contains(.,'Approved')]");

    /**
     *Click on the Agree button
     */
    
    await adminPage.click('//button[contains(.,"Agree")]');
    await expect(adminPage.locator('text=Seller updated successfully!')).toBeVisible();

    // Save credentials to a file
    fs.writeFileSync('credentials.json', JSON.stringify(sellerCredentials));

});
