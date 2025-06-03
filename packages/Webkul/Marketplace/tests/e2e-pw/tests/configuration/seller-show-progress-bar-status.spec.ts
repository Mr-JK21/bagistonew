import { appendFile } from "fs";
import { sellerLogin } from "../../seller-setup";
import { test, expect } from "../../setup";

test.describe('Show Progress Bar', () => {

    const randomID = Date.now();

    const productDetails = {
        sku: 'SKU'+ randomID,
        product_name: 'Product Name'+ randomID,
        short_description: ' Fashion Product',
        description: ' Fashion Product',
        meta_title: 'Meta Title',
        meta_keywords: 'Meta Keyword',
        meta_description: 'Meta Description',
        price: '100',
        weight: '0.322',
        inventories: '50',
    };

    test('Show Progress Bar For Seller -> Create New Product', async ({ adminPage }) => {
        /**
         * Expect result to redirect the admin dashboard URL
         */
        await expect(adminPage).toHaveURL('http://192.168.15.80/Bagisto-v222/mp-v/public/admin/dashboard');

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

        const seller_create_product =  await adminPage.locator('//input[@id="marketplace[settings][product][seller_can_create]"]').isChecked();
        const show_progress_bar = await adminPage.locator('//input[@id="marketplace[settings][product][show_progress_bar]"]').isChecked();

        if(seller_create_product){
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

                await expect(adminPage.locator('//p[contains(.," Create New Products ")]')).toBeVisible();

            /**
             * Create the Simple Product
             */

                /**
                 * Select the Product Type [index 0]
                 */
                await adminPage.selectOption('//select[@name="type"]', { index: 0 });

                /**
                 * Select the Attribute Family [Index 0]
                 */
                await adminPage.selectOption('//select[@name="attribute_family_id"]', { index: 0 });
                await adminPage.waitForTimeout(2000);
            
                /**
                 * Fill the Sku using the Random fuction
                 */
                await adminPage.fill('//input[@name="sku"]', productDetails.sku);

                /**
                 * Click to Continue button
                 */
                await adminPage.click('//button[contains(.,"Continue")]');

                await adminPage.waitForTimeout(2000);
                (await adminPage.waitForSelector('div.fixed.top-5')).isVisible();
            if ( show_progress_bar ) {    

                /**
                 * Checked the Expected pop-up message "Product updated successfully"
                 */
                await expect(adminPage.locator('div.relative.h-4')).toBeVisible();
                console.log('Display');
            } else {
        
                /**
                 * Checked the Expected pop-up message "Product updated successfully"
                 */
                await expect(adminPage.locator('div.relative.h-4')).not.toBeVisible();
                console.log('Not Display');
            }
        } else {
            console.log('Unbale to Create the Product');
        }
    });

    test('Show Progress Bar For Seller -> Updated Product', async ({ adminPage }) => {

         /**
         * Expect result to redirect the admin dashboard URL
         */
         await adminPage.goto('http://192.168.15.80/Bagisto-v222/mp-v/public/admin/dashboard');

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
 
         const show_progress_bar = await adminPage.locator('//input[@id="marketplace[settings][product][show_progress_bar]"]').isChecked();
       
         /**
             * Navigate to the Home adminPage.
             */
         await adminPage.goto('http://192.168.15.80/Bagisto-v222/mp-v/public/');        
          
         await sellerLogin(adminPage);
         /**
          * Expexted to login the Seller panel
          */
         await expect(adminPage).toHaveURL('http://192.168.15.80/Bagisto-v222/mp-v/public/seller/dashboard')
         /**
          * Click to the Product section
          */
         await adminPage.click('//span[contains(.," Products ")]');

         await adminPage.click('//span[contains(.," Products ")]');

        /**
         * Click to the Add New Product button
         */
        await adminPage.click('//a[contains(.,"Add New Product")]')
        await expect(adminPage.locator('//p[contains(.," Create New Products ")]')).toBeVisible();

    /**
     * Create the Simple Product
     */

        /**
         * Select the Product Type [index 0]
         */
        await adminPage.selectOption('//select[@name="type"]', { index: 0 })
        /**
         * Select the Attribute Family [Index 0]
         */
        await adminPage.selectOption('//select[@name="attribute_family_id"]', { index: 0 });
        await adminPage.waitForTimeout(2000);
    
        /**
         * Fill the Sku using the Random fuction
         */
        await adminPage.fill('//input[@name="sku"]', productDetails.sku)
        /**
         * Click to Continue button
         */
        await adminPage.click('//button[contains(.,"Continue")]');

        (await adminPage.waitForSelector('div.fixed.top-5')).isVisible();

        await adminPage.goto('http://192.168.15.80/Bagisto-v222/mp-v/public/seller/products');

        /**
         * Click to the edit button of the First Product using index
         */
        await adminPage.click('(//span[@title="Edit"])[1]')
        await adminPage.waitForTimeout(2000);

        if ( show_progress_bar ) {

            await expect(adminPage.locator('div.relative.h-4')).toBeVisible();
            console.log('Visible');
        } else {
            await adminPage.waitForTimeout(2000);
            await expect(adminPage.locator('div.relative.h-4')).not.toBeVisible();
            console.log('Not Visible');
        }
    });
});