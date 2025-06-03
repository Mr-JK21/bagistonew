import { test, expect } from "../../setup";

test('seller flag status', async ({ adminPage }) => {

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
     * Store the Value for the status of Seller Flag
     */
    const seller_flag =  await adminPage.locator('//input[@id="marketplace[settings][seller][flag_enabled]"]').isChecked();

    /**
     * Go to the Home Page
     */
    await adminPage.goto('http://192.168.15.80/Bagisto-v222/mp-v/public/');
    await adminPage.waitForTimeout(5000);

    /**
    * Click the First product of the New Products Section on the Home Page
    */
    await adminPage.click('(//div[contains(@class, "flex gap-8")])[2]/div[1]');

    /**
     * Create a variable to check this is the seller product or not
     */
    const seller_product = await adminPage.locator('a.text-lg ').isVisible();

    /**
     * If Check the First Product is seller product
     */
    if(seller_product){
        // console.log('Seller product');
        /**
         * Click to the Available Seller
         */
        await adminPage.click('//a[@class="text-lg font-semibold text-navyBlue"]');        
        await expect(adminPage.locator('//input[@placeholder="Search for Products"]')).toBeVisible();

        if (seller_flag) {
            // console.log('"Report Issue" button is displayed');

            /**
             * To check the Seller Report flag button is visible or not
             */
            await adminPage.waitForTimeout(2000);
            await expect(adminPage.locator('(//span[contains(.," Report Issue ")])[1]')).toBeVisible();
        } else {
            await expect(adminPage.locator('(//span[contains(.," Report Issue ")])[1]')).not.toBeVisible();
            // console.log('Report issue button is not displayed');
        }
    } else {
        /**
         * This Product is admin Product
         */
        console.log('Admin Product');
    }
});