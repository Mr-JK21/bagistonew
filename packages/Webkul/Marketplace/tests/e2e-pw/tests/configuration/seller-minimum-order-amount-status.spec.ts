import { sellerLogin } from "../../seller-setup";
import { test, expect } from "../../setup";

test('Seller minimun order amount', async ({ adminPage }) => {

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
     * 
     */
    const minimum_order_amount_status = await adminPage.locator('//input[@id="marketplace[settings][seller][enable_minimum_order_amount]"]').isChecked();

    if (minimum_order_amount_status) {
        console.log('Is Enable');

        /**
         * Call the Seller Login Function
         */
        await sellerLogin(adminPage);

        /**
        * Expexted to login the Seller panel
        */
        await expect(adminPage).toHaveURL('http://192.168.15.80/Bagisto-v222/mp-v/public/seller/dashboard')

        /**
         * Click to the Product section
         */
        await adminPage.click('//span[contains(.," Manage Profile ")]');

        await adminPage.waitForSelector('//p[contains(.," Manage Profile ")]')

        await expect(adminPage.locator('//h3[contains(.," Minimum Order Amount ")]')).toBeVisible();

        await adminPage.waitForTimeout(2000);
        /**
         * Go to Admin panel -> Seller Sections
         */
        await adminPage.goto('http://192.168.15.80/Bagisto-v222/mp-v/public/admin/marketplace/sellers');
        await adminPage.click('(//span[@title="Edit"])[1]');

        await expect(adminPage.locator('//label[contains(.," Minimum Order Amount ")]')).toBeVisible();

    } else {
        await expect(adminPage.locator('//label[contains(.," Minimum Order Amount ")]')).not.toBeVisible();
        console.log('Is Disabled');
    }
});