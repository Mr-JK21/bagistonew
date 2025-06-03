import { test, expect } from "../../setup";

test.describe('livestream management at admin', () => {

    test('Marketplace Configuration', async ({ adminPage }) => {

        /**
         * Create the Configuration Credentials
         */
        const marketplaceCredential = {
            street_address : '1',
            admin_commission: '20',
            red_flag: '12',
            limit_count: '5',
            banner_title: 'Create a Velocity ',
            banner_description: 'Velocity can be a great platform for selling your products to new-age businesses in India.',
            banner_button_title: 'Open Shop',
        };

        /**
         * Navigate to the Configuration section
         */
        await adminPage.goto('http://192.168.15.80/Bagisto-v222/mp-v/public/admin/configuration');

        /**
         * Click on the Marketplace Configuration
         */
        await adminPage.click('(//p[contains(.," Manage Marketplace ")])[2]');
        
        await adminPage.waitForSelector('//p[@class="text-xl font-bold text-gray-800 dark:text-white"]');

        /**
         * Create a variable to store the status value
         */
        const status = await adminPage.locator('label > div').first();

        /**
         * Check if status is Enable/Disable
         */
        if (!(await status.isChecked())) {
            /**
             * If status is disabled So that first Enable the Marketplace status 
             */
            await status.click();

            /**
             * Click the Configuration details
             */
            await adminPage.fill('//input[@id="marketplace[settings][general][lines_in_street_address]"]', marketplaceCredential.street_address );
            await adminPage.fill('//input[@id="marketplace[settings][general][admin_commission_percentage]"]', marketplaceCredential.admin_commission);
            await adminPage.fill('//input[@id="marketplace[settings][seller][red_flag_limit]"]', marketplaceCredential.red_flag);
            await adminPage.fill('//input[@id="marketplace[settings][featured_sellers][limit_count]"]', marketplaceCredential.limit_count);

            await adminPage.fill('//input[@id="marketplace[settings][landing_page][banner_title]"]',marketplaceCredential.banner_title);
            await adminPage.fill('//textarea[@id="marketplace[settings][landing_page][banner_description]"]',marketplaceCredential.banner_description);
            await adminPage.fill('//input[@id="marketplace[settings][landing_page][banner_btn_title]"]',marketplaceCredential.banner_button_title);

            /**
             * Click on save Button
             */
            await adminPage.click('//button[contains(.," Save Configuration ")]');

            /**
             * Expected Result: To checked that the pop-up message is displayed or not
             */
            await expect(adminPage.getByText('Configuration saved successfully')).toBeVisible();
            await adminPage.waitForTimeout(2000);
            
            /**
             * Expected result that the Marketplace section Should be displayed on Side Menu.
             */
            await expect(adminPage.locator('(//p[contains(.," Marketplace")])[1]')).toBeVisible();
        } 

        /**
         * Expected result that the Marketplace section Should be displayed on Side Menu.
         */
        await expect(adminPage.locator('(//p[contains(.," Marketplace")])[1]')).toBeVisible();   
    });
});