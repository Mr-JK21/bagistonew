import { test, expect } from '../../setup';
import { sellerLogin } from '../../seller-setup';
import { fillInTinymce } from "../../utils/tinymce";

test.describe('livestream management at admin', () => {

    test('Approval Required Status For Product', async ({ adminPage }) => {
        /**
         * To create the random id
         */
        const randomID = Date.now();

        const productDetails = {
            name: 'Product' + randomID,
            sku: 'SKU' + randomID,
            short_description: 'Fashion Products',
            description: 'Fashion Products',
            meta_title: 'meta title',
            meta_des: 'Meta Description',
            meta_keyword: 'Meta Keywords',
            price: '100',
            weight: '2.02',
            inventory: '100'
            
        };
    
        await adminPage.goto('http://192.168.15.80/Bagisto-v222/mp-v/public/admin/configuration/marketplace/settings');
        
        await adminPage.waitForTimeout(2000);
        /**
         * To create the variable to store the status value
         */
        const product_approval = await adminPage.locator('//input[@id="marketplace[settings][product][approval_required]"]');
    
        /**
         * Checked that Is module is Enabled/Disabled
         */
        if (await product_approval.isChecked()){
            console.log('Approval required is Enable');

            /**
             * Login the seller with the credential.json file
             */
            await sellerLogin(adminPage);


            await adminPage.goto('http://192.168.15.80/Bagisto-v222/mp-v/public/seller/products');
        
            await adminPage.waitForTimeout(2000);
            /**
             * Click to the Add New Product button
             */
            await adminPage.click('//a[contains(.,"Add New Product")]');
        
            /**
             * Check that after redirect to the seller product create adminPage
             */
            await expect(adminPage).toHaveURL('http://192.168.15.80/Bagisto-v222/mp-v/public/seller/products/create');
        
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
            await adminPage.click('//button[contains(.,"Continue")]')
        
            /**
             * Fill the Product Name
             */
            await adminPage.waitForTimeout(5000);
            
            await adminPage.fill('//input[@name="name"]', productDetails.name);
        
            
            /**
             * Fill the Description and Short DEscription using the Function
             */
            await fillInTinymce(adminPage, '#short_description_ifr', productDetails.short_description);
            await fillInTinymce(adminPage, '#description_ifr', productDetails.description);
        
            /**
             * Fill the Meta Title details
             */
            await adminPage.fill('//textarea[@name="meta_title"]', productDetails.meta_title);
            await adminPage.fill('//textarea[@name="meta_keywords"]', productDetails.meta_keyword);
            await adminPage.fill('//textarea[@name="meta_description"]', productDetails.meta_des);
        
            /**
             * Enter the Price
             */
            await adminPage.fill('//input[@name="price"]', productDetails.price);
            
            /**await adminPage.waitForTimeout(5000);
             * Enter the Weight
             */
            await adminPage.fill('//input[@name="weight"]', productDetails.weight);
        
            /**
             * Enable the all Status
             */
            await adminPage.locator('div:nth-child(3) > .relative > label').click();
            await adminPage.locator('div:nth-child(4) > .relative > label').click();
            await adminPage.locator('div:nth-child(5) > .relative > label').click();
            await adminPage.locator('div:nth-child(3) > div:nth-child(6)').click();
            await adminPage.locator('div:nth-child(6) > .relative > label').click();
            await adminPage.fill('//input[@name="inventories[1]"]' , productDetails.inventory);
        
            /**
             * Click on the save button
             */
            await adminPage.click('//button[contains(.,"Save Product") ]')
        
            /**
             * Checked the Expected pop-up message "Product updated successfully"
             */
            await adminPage.waitForTimeout(2000);
            await expect(adminPage.locator('div.fixed.top-5')).toHaveText('Product updated successfully');
        
            /**
             * Checked that the Created Product is Disapproved When the Status is Enabled
             */
            // await expect(adminPage.locator('//div[@class="flex items-center justify-between gap-x-4"]/div/div/p[2]')).toHaveText('Disapproved');

            const approvedElement = adminPage.locator('//p[contains(text(),"Disapproved")]').nth(0);

            await expect(approvedElement).toBeVisible();

        } else {
            console.log('Approval required is disable');

            await sellerLogin(adminPage);

             await adminPage.goto('http://192.168.15.80/Bagisto-v222/mp-v/public/seller/products');
             /**
              * Click to the Add New Product button
              */
             await adminPage.click('//a[contains(.,"Add New Product")]');
        
             /**
              * Check that after redirect to the seller product create adminPage
              */
             await expect(adminPage).toHaveURL('http://192.168.15.80/Bagisto-v222/mp-v/public/seller/products/create');
        
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
            await adminPage.click('//button[contains(.,"Continue")]')
        
            /**
             * Fill the Product Name
             */
            await adminPage.waitForTimeout(5000);
            
            await adminPage.fill('//input[@name="name"]', productDetails.name);
        
            
            /**
             * Fill the Description and Short DEscription using the Function
             */
            await fillInTinymce(adminPage, '#short_description_ifr', productDetails.short_description);
            await fillInTinymce(adminPage, '#description_ifr', productDetails.description);
        
            /**
             * Fill the Meta Title details
             */
            await adminPage.fill('//textarea[@name="meta_title"]', productDetails.meta_title);
            await adminPage.fill('//textarea[@name="meta_keywords"]', productDetails.meta_keyword);
            await adminPage.fill('//textarea[@name="meta_description"]', productDetails.meta_des);
        
            /**
             * Enter the Price
             */
            await adminPage.fill('//input[@name="price"]', productDetails.price);
            
            /**
             * Enter the Weight
             */
            await adminPage.fill('//input[@name="weight"]', productDetails.weight);
        
            /**
             * Enable the all Status
             */
            await adminPage.locator('div:nth-child(3) > .relative > label').click();
            await adminPage.locator('div:nth-child(4) > .relative > label').click();
            await adminPage.locator('div:nth-child(5) > .relative > label').click();
            await adminPage.locator('div:nth-child(3) > div:nth-child(6)').click();
            await adminPage.locator('div:nth-child(6) > .relative > label').click();
            await adminPage.fill('//input[@name="inventories[1]"]' , productDetails.inventory);
        
             /**
              * Click on the save button
              */
             await adminPage.click('//button[contains(.,"Save Product") ]')
        
             /**
              * Checked the Expected pop-up message "Product updated successfully"
              */
             await adminPage.waitForTimeout(2000);
             await expect(adminPage.locator('div.fixed.top-5')).toHaveText('Product updated successfully');
        
             /**
              * Checked that the Product is Approved when Disabled the status
              */
             
             const approvedElement = adminPage.locator('(//p[contains(text(),"Approved")])[3]').nth(0);

             await expect(approvedElement).toBeVisible({timeout: 5000});        
        }
    
    });

});