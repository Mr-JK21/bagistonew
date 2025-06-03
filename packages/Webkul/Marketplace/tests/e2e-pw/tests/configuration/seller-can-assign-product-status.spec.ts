import { test, expect } from "../../setup";
import { sellerLogin } from "../../seller-setup";

test('seller can assign product status', async ({ adminPage }) => {

    await adminPage.goto('http://192.168.15.80/Bagisto-v222/mp-v/public/admin/dashboard')
    
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
    const seller_can_assign_product = await adminPage.locator('//input[@id="marketplace[settings][product][seller_can_assign]"]').isEnabled();
    
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
     *click on the banner button.
     */
    await adminPage.click('//a[@class="primary-button flex items-center gap-2.5"]');

    /**
     *Check the Customer is redirected to Seller Login/Register adminPage.
     */
    await expect(adminPage).toHaveURL('http://192.168.15.80/Bagisto-v222/mp-v/public/seller/register');

    /**
     * Click to the Sign In button
     */
    await adminPage.click('//a[contains(.," Sign In")]')

    /**
     * Login the seller with the saved credential
     */
    await sellerLogin(adminPage);

    /**
     * Expexted to login the Seller panel
     */
    await adminPage.waitForURL('http://192.168.15.80/Bagisto-v222/mp-v/public/seller/dashboard');
    
    /**
     * Click to the Product section
     */
    await adminPage.click('//span[contains(.," Products ")]');

    /**
     * Click to the Add New Product button
     */
    await adminPage.click('//a[contains(.,"Add New Product")]');
    
     /**
     *Expect result:  To check this text is "Search Products" Available
     */
    if (seller_can_assign_product){  
        /**
         *Expect result:  To check this text is "Search Products" Available
         */
        await expect(adminPage.locator('(//p[contains(.," Search Products ")])[1]')).toBeVisible();
      
    } else {
        /**
         *Expect result:  To check this text is not "Search Products" Available
         */
        await expect(adminPage.locator('(//p[contains(.," Search Products ")])[1]')).not.toBeVisible();

    }
});