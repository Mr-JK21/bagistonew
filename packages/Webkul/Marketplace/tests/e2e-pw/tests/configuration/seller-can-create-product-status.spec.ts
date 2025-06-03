import { test, expect } from "../../setup";
import { sellerLogin } from "../../seller-setup";

test('Seller can Create Product Status', async ({ adminPage }) => {

    /**
     * Expect result to redirect the admin dashboard URL
     */
    await expect(adminPage).toHaveURL('http://192.168.15.80/Bagisto-v222/mp-v/public/admin/dashboard')
    
    /**
     * Navigate to the Configuration section
     */
    await adminPage.click('//p[contains(.," Configure ")]');

    /**
     * Click to the Marketplace configuration
     */
    await adminPage.click('(//p[contains(.," Manage Marketplace ")])[2]');

    /**
     * Expect result to redirect the Marketpalce settings URL
     */
    await expect(adminPage).toHaveURL('http://192.168.15.80/Bagisto-v222/mp-v/public/admin/configuration/marketplace/settings');
    await adminPage.waitForTimeout(2000);

    /**
     * Create the Variable and store the value of the status
     */
    const seller_create_product = await adminPage.locator('//input[@id="marketplace[settings][product][seller_can_create]"]').isChecked();
    
    /**
     *Navigate to the Home adminPage.
     */
     await adminPage.goto('http://192.168.15.80/Bagisto-v222/mp-v/public/');
     
    /**
     *Navigate to the Marketplace adminPage.
     */
    await adminPage.click('(//a[@aria-label="Sell"])[1]');
     
    /**
     *Expect the URL to be the Marketplace adminPage.
     */
    await expect(adminPage).toHaveURL('http://192.168.15.80/Bagisto-v222/mp-v/public/marketplace');

    /**
     *Navigate to the Seller Login/Register adminPage.
     */
    await adminPage.click('//a[@class="primary-button flex items-center gap-2.5"]');
    /**
     * call the seller sign in Function
     */
    await sellerLogin(adminPage);
    
    /**
     * Expexted to login the Seller panel
     */
    await expect(adminPage).toHaveURL('http://192.168.15.80/Bagisto-v222/mp-v/public/seller/dashboard')
    
    /**
     * Click to the Product section
     */
    await adminPage.click('//span[contains(.," Products ")]');
    
    /**
     * Click to the Add New Product button
     */
    await adminPage.click('//a[contains(.,"Add New Product")]');

    if (seller_create_product) { 
        // console.log("Is visible");   
        
        /**
         * To check that the Create New Products section is visible
         */
        await expect(adminPage.locator('//p[contains(.," Create New Products ")]')).toBeVisible();
        await adminPage.waitForTimeout(2000);
        //  await adminPage.locator('//p[contains(.," Create New Products ")]').isVisible()
    } else {
        // console.log("Not visible");

        /**
         * To check that the Create New Products section is not visible
         */
        await expect(adminPage.locator('//p[contains(.," Create New Products ")]')).not.toBeVisible();
        await adminPage.waitForTimeout(2000);
    }
});