import { test, expect } from '@playwright/test';

test('Check the seller review approval', async ({ page }) => {
    
    /**
     * 0.3 scale factor
     */
    await page.evaluate(() => {
        document.body.style.zoom = '50%';
    });

    /**
     * customer login
     */
    await page.goto('http://192.168.15.80/Bagisto-v222/marketplace/public/');
    await page.click('//span[@aria-label="Profile"]');
    await page.click('(//a[contains(.," Sign Up ")])[1]');
    await expect(page).toHaveURL('http://192.168.15.80/Bagisto-v222/marketplace/public/customer/register');

    /**
     * Create a random value
     */
    const randomID = Date.now();

    /**
     * Fill the customer registration form
     */

    await page.fill('//input[@placeholder="First Name"]', 'Customer');
    await page.fill('//input[@placeholder="Last Name"]', 'Customer' + randomID);
    await page.fill('//input[@placeholder="email@example.com"]', 'customer'+ randomID + '@example.com');
    await page.fill('//input[@placeholder="Password"]', 'admin123');
    await page.fill('//input[@placeholder="Confirm Password"]', 'admin123');
    await page.click('//button[contains(.," Register ")]');

    /**
     * Check the customer login URL
     */
    await expect(page).toHaveURL('http://192.168.15.80/Bagisto-v222/marketplace/public/customer/login');

    await page.click('//a[@aria-label="Bagisto"]');

/**
 * Check the customer can review the seller profile without login
 */
    /** 
     * Check the Home Page URL
     */
    await expect(page).toHaveURL('http://192.168.15.80/Bagisto-v222/marketplace/public/');

    /**
     * Click the first product of the New Products Section on the Home Page
     */
    
    await page.click('(//div[contains(@class, "flex gap-8")])[1]/div[1]');
    
    /**
     * Click on the Seller Name to navigate to the Seller Shop Page
     */
    await page.click('//a[@class="text-lg font-semibold text-navyBlue"]');

    /**
     * Check the Seller shop is visible or not Using the locator of the Share Button in the Seller Shop Page
     */
    await expect(page.locator('//span[contains(.,"Share")]')).toBeVisible();

    /**
     * Click to the Seller Review Section
     */
    await page.click('((//div[@class="flex gap-5"])[2]/button[2])');  

    /**
     * Click the Write Review Button
     */
    await page.click('(//span[contains(.," Write a Review")])[2]')

    /**
     * Checked the Alert Message
     */
    page.on('dialog', async dialog => {
        console.log(`Alert Message: ${dialog.message()}`);
        await dialog.accept(); // Click 'OK'
    });

    /**
     * Click the Agree Button
     */
    await page.click('//button[contains(.,"Agree")]');

    await page.waitForTimeout(2000);

    /**
     * Check the customer login URL
     */
    await expect(page).toHaveURL('http://192.168.15.80/Bagisto-v222/marketplace/public/customer/login');

    /**
     * Fill the customer login form
     */

    await page.fill('//input[@placeholder="email@example.com"]', 'customer'+ randomID + '@example.com');
    await page.fill('//input[@placeholder="Password"]', 'admin123');
    await page.click('//button[contains(.," Sign In ")]');

    /**
     * Check the Home Page URL
     */
    await expect(page).toHaveURL('http://192.168.15.80/Bagisto-v222/marketplace/public/');

    /**
     * Click the first product of the New Products Section on the Home Page
     */

    await page.click('(//div[contains(@class, "flex gap-8")])[1]/div[1]');
    
    /**
     * Click on the Seller Name to navigate to the Seller Shop Page
     */
    await page.click('//a[@class="text-lg font-semibold text-navyBlue"]');

    /**
     * Check the Seller shop is visible or not Using the locator of the Share Button in the Seller Shop Page
     */
    await expect(page.locator('//span[contains(.,"Share")]')).toBeVisible();

    /**
     * Click to the Seller Review Section
     */
    await page.click('((//div[@class="flex gap-5"])[2]/button[2])');

    /**
     * Click the Write Review Button
     */
    await page.click('(//span[contains(.," Write a Review")])[2]')

    /**
     * select the rating according using the Index
     */
    await page.click('(//span[@class="icon-star-fill cursor-pointer text-2xl"])[2]');

    /**
     * Fill the Review Form
     */
    await page.fill('//input[@placeholder="Title"]', 'Nice Product Review title');
    await page.fill('//textarea[@placeholder="Comment"]', 'Reviwe Comment');

    /**
     * Click the Submit Button
     */
    await page.click('(//button[contains(.,"Submit")])[3]');
 
/**
 * Check the seller review approval by admin
 */
    /**
     * Navigate to the Admin Login Page
     */
    await page.goto("http://192.168.15.80/Bagisto-v222/marketplace/public/admin/login");
    // await page.setViewportSize({ width: 1280, height: 720 });
    
    /**
     * Fill the Admin Login Form With the Credentials
     */
    await page.fill("//input[@placeholder='Email Address']", "admin@example.com");
    await page.fill("//input[@placeholder='Password']", "admin123");
    
    /**
     * Click Sign In button
     */
    await page.click("//button[contains(.,' Sign In ')]");
    await page.waitForTimeout(2000);
    
    /**
     * Check the Admin Dashboard URL
     */
    await expect(page).toHaveURL("http://192.168.15.80/Bagisto-v222/marketplace/public/admin/dashboard");

    /**
     * Navigate to Marketplace section
     */
    await page.click("//p[contains(.,'Marketplace')]");
   
    /**
     * Navigate to Seller Reviews
     */ 
    await page.click("//a[@href='http://192.168.15.80/Bagisto-v222/marketplace/public/admin/marketplace/seller-reviews']");
    await page.waitForTimeout(2000);

/**
 * Check the seller review approval one by one
 */
    /**
     * Select the first checkbox using the Index
     */
    await page.click("(//label[@class='icon-uncheckbox peer-checked:icon-checked cursor-pointer rounded-md text-2xl peer-checked:text-blue-600'])[1]");
    
    /**
     * Click on Select Action dropdown
     */
    await page.click("//span[contains(.,'Select Action')]");
    // await page.waitForTimeout(2000);
    
    /**
     * Hover over Update Status option and display the dropdown options
     */
    const updateStatus = await page.locator("xpath=//span[contains(.,'Update Status')]");
    await updateStatus.hover();
    await page.waitForTimeout(2000);
    
    /**
     * Select the Approval button
     */
    await page.click("(//a[@class='whitespace-no-wrap block rounded-t px-4 py-2 text-sm text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-950'])[2]");
    
    /**
     * Click on the Agree button
     */
    await page.click("//button[@class='primary-button']");
    

    /**
     * Check the Success Message
     */
    await expect(page.locator('text=Seller reviews updated successfully.')).toBeVisible();
});