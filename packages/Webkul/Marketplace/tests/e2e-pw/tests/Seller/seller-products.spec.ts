import { test, expect, Page } from '@playwright/test';
import { fillInTinymce } from "../../utils/tinymce";
import { sellerLogin } from "../../seller-setup";

    /**
     * To create the random id
     */
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
    }

test.describe('Product management', () => {

    test('Create the Seller Simple Products', async ({ page }) => {

        /**
         * Call the sellerLogin function
         */
        await sellerLogin(page);
        /**
         * Click to the Product section
         */
        await page.click('//span[contains(.," Products ")]');
        /**
         * Click to the Add New Product button
         */
        await page.click('//a[contains(.,"Add New Product")]');
        /**
         * Check that after redirect to the seller product create page
         */
        await expect(page).toHaveURL('http://192.168.15.80/Bagisto-v222/mp-v/public/seller/products/create');
        /**
         * Select the Product Type [index 0]
         */
        await page.selectOption('//select[@name="type"]', { index: 0 });

        /**
         * Select the Attribute Family [Index 0]
         */
        await page.selectOption('//select[@name="attribute_family_id"]', { index: 0 });
        await page.waitForTimeout(2000);

        /**
         * Fill the Sku using the Random fuction
         */
        await page.fill('//input[@name="sku"]',productDetails.sku );

        /**
         * Click to Continue button
         */
        await page.click('//button[contains(.,"Continue")]')

        await page.waitForTimeout(2000);

        /**
         * Fill the Product Name
         */
        await page.fill('//input[@name="name"]', productDetails.product_name);

        /**
         * Fill the Description and Short DEscription using the Function
         */
        await fillInTinymce(page, '#short_description_ifr', productDetails.short_description);
        await fillInTinymce(page, '#description_ifr', productDetails.description);

        /**
         * Fill the Meta Title details
         */
        await page.fill('//textarea[@name="meta_title"]', productDetails.meta_title);
        await page.fill('//textarea[@name="meta_keywords"]', productDetails.meta_keywords);
        await page.fill('//textarea[@name="meta_description"]', productDetails.meta_description);

        /**
         * Enter the Price
         */
        await page.fill('//input[@name="price"]', productDetails.price);

        /**
         * Enter the Weight
         */
        await page.fill('//input[@name="weight"]', productDetails.weight);

        /**
         * Enable the all Status
         */
        await page.locator('div:nth-child(3) > .relative > label').click();
        await page.locator('div:nth-child(4) > .relative > label').click();
        await page.locator('div:nth-child(5) > .relative > label').click();
        await page.locator('div:nth-child(3) > div:nth-child(6)').click();
        await page.locator('div:nth-child(6) > .relative > label').click();
        await page.fill('//input[@name="inventories[1]"]' , productDetails.inventories);

        /**
         * Click on the save button
         */
        await page.click('//button[contains(.,"Save Product") ]')

        /**
         * Checked the Expected pop-up message "Product updated successfully"
         */
        await expect(page.locator('div.fixed.top-5')).toHaveText('Product updated successfully');

    });

    test('Create the Seller Configurable Products', async ({ page }) => {
        
        /**
         * Call the sellerLogin function
         */
        await sellerLogin(page);

        /**
         * Click to the Product section
         */
        await page.click('//span[contains(.," Products ")]');
    
        /**
         * Click to the Add New Product button
         */
        await page.click('//a[contains(.,"Add New Product")]');
    
        /**
         * Check that after redirect to the seller product create page
         */
        await expect(page).toHaveURL('http://192.168.15.80/Bagisto-v222/mp-v/public/seller/products/create');
        
        /**
         * Select the Product Type [index 0]
         */
        await page.selectOption('//select[@name="type"]', { index: 1 });
        
        /**
         * Select the Attribute Family [Index 0]
         */
        await page.selectOption('//select[@name="attribute_family_id"]', { index: 0 });

        /**
         * Fill the Sku using the Random fuction
         */
        await page.fill('//input[@name="sku"]',productDetails.sku );

        /**
         * Click to Continue button
         */
        await page.click('//button[contains(.,"Continue")]')

               
        /**
         * Delete Red,Green and Yellow option
         */
        await page.getByRole('paragraph').filter({ hasText: 'Red' }).locator('span').click();
        await page.getByRole('paragraph').filter({ hasText: 'Green' }).locator('span').click();
        await page.getByRole('paragraph').filter({ hasText: 'Yellow' }).locator('span').click();

        /**
         * Delete the M,L and XL size
         */
        await page.locator('div:nth-child(2) > div > p:nth-child(2) > .mp-delete-icon').click();
        await page.locator('form').filter({ hasText: 'Product Type Simple' }).locator('span').nth(3).click();
        await page.getByRole('paragraph').filter({ hasText: 'XL' }).locator('span').click();
        
        /**
         * Click to Continue button
         */
        await page.click('//button[contains(.,"Continue")]');

        await page.locator('//div[@class="mt-3.5 rounded-xl border bg-white p-5"]').isVisible();
        
        await page.waitForTimeout(8000);
        /**
         * Fill the Product Name
         */
        await page.fill('//input[@id="name"]', productDetails.product_name);

        /**
         * Fill the Description and Short DEscription using the Function
         */
        await fillInTinymce(page, '#short_description_ifr', productDetails.short_description);
        await fillInTinymce(page, '#description_ifr', productDetails.description);

        /**
         * Fill the Meta Title details
         */
        await page.fill('//textarea[@name="meta_title"]', productDetails.meta_title);
        await page.fill('//textarea[@name="meta_keywords"]', productDetails.meta_keywords);
        await page.fill('//textarea[@name="meta_description"]', productDetails.meta_description);

        /**
         * Select All Variants
         */
        await page.click('//span[@for="select-all-variants"]');
        
        await page.waitForSelector('//button[contains(.," Select Action ")]');

        /**
         * Click on the Select Action Button
         */
        await page.click('//button[contains(.," Select Action ")]');

        /**
         * Select the Edit price option
         */
        await page.click('//div/ul/li[3]');

        /**
         * Click on Agree button on the Modal
         */
        await page.click('//button[contains(.,"Agree")]');

        /**
         * Enter the Price For the all variants
         */
        await page.fill('//input[@name="price"]', productDetails.price);

        /**
         * Click on "Apply To All" button
         */
        await page.click('//button[contains(.," Apply to All ")]');

        /**
         * Click on "Save" button
         */
        await page.click('//button[@class="primary-button ltr:mr-11 rtl:ml-11"]');
        await page.waitForTimeout(2000);

        /**
         * Select All Variants
         */
        await page.click('//span[@for="select-all-variants"]');
        
        /**
         * Click on the Select Action Button
         */
        await page.click('//button[contains(.," Select Action ")]');

        /**
         * Select the Edit price option
         */
        await page.click('//div/ul/li[4]');

        /**
         * Click on Agree button on the Modal
         */
        await page.click('//button[contains(.,"Agree")]');

        /**
         * Enter the Price For the all variants
         */
        await page.fill('//input[@name="inventories[1]"]', productDetails.inventories);

        /**
         * Click on "Apply To All" button
         */
        await page.click('//button[contains(.," Apply to All ")]');

        /**
         * Click on "Save" button
         */
        await page.click('//button[@class="primary-button ltr:mr-11 rtl:ml-11"]');
        await page.waitForTimeout(2000);
        
        /**
         * Enter the Weight
         */
        // await page.fill('//input[@name="weight"]', productDetails.weight);

        /**
         * Enable the all Status
         */
        await page.locator('.relative > label').first().click();
        await page.locator('div:nth-child(3) > .relative > label').click();
        await page.locator('div:nth-child(6) > .relative > label').click();
        await page.locator('div:nth-child(4) > .relative > label').click();

        /**
         * Click on the save button
         */
        await page.click('//button[contains(.,"Save Product") ]')

        /**
         * Checked the Expected pop-up message "Product updated successfully"
         */
        await expect(page.locator('div.fixed.top-5')).toHaveText('Product updated successfully');
    });
});