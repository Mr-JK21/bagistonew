import { test, expect } from "../../setup";

test('Product Flag', async ({ adminPage}) => {

    /**
     * Navigate to the Configuration section
     */
    await adminPage.goto('http://192.168.15.80/Bagisto-v222/mp-v/public/admin/configuration');

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
     * Create a variable to store the Product flag status
     */

    const product_flag = await adminPage.locator('//input[@id="marketplace[settings][product][flag_enabled]"]') 

    if(await product_flag.isChecked()){
        
        /**
         * Go to the Home adminPage
         */
        await adminPage.goto('http://192.168.15.80/Bagisto-v222/mp-v/public/');
        await adminPage.waitForTimeout(2000);

        /**
        * Click the First product of the New Products Section on the Home adminPage
        */
        await adminPage.click('(//div[contains(@class, "flex gap-8")])[2]/div[1]');

        await adminPage.waitForTimeout(5000);
        /**
         * Create a variable to check this is the seller product or not
         */
        const seller_product = await adminPage.locator('a.text-lg').isVisible();

        /**
         * If the First Product is seller product
         */
        if(seller_product){
            console.log('Seller product');
            
            /**
             * To check the Report flag button is visible or not
             */
            await expect(adminPage.locator('//span[@class="flex cursor-pointer items-center gap-2.5"]')).toBeVisible();
        }else{
            /**
             * This Product is admin Product
             */
            console.log('Admin Product')

            await expect(adminPage.locator('//span[@class="flex cursor-pointer items-center gap-2.5"]')).not.toBeVisible();
        }
        console.log('Product Flag Status is Enabled');
    }else{
        /**
         * Go to the Home adminPage
         */
        await adminPage.goto('http://192.168.15.80/Bagisto-v222/mp-v/public/');
        await adminPage.waitForTimeout(2000);

        /**
        * Click the First product of the New Products Section on the Home adminPage
        */
        await adminPage.click('(//div[contains(@class, "flex gap-8")])[1]/div[1]');
        await adminPage.waitForTimeout(2000);

        /**
         * Create a variable to check this is the seller product or not
         */
        const seller_product = await adminPage.locator('a.text-lg ')

        /**
         * If the First Product is seller product
         */
        if(await seller_product.isVisible()){
            console.log('Seller product but Not Visible the Report Flag button');
            
            /**
             * Expected result: To check that the Report flag is not Visible when the status is disabled
             */
            await expect(adminPage.locator('//span[@class="flex cursor-pointer items-center gap-2.5"]')).not.toBeVisible();
        }else{
            console.log('Admin Product');
        }
        console.log('Not Enable');
    }
});