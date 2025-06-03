import { test, expect } from "../../setup";

test.describe('arketplace -> order section', () => {

    test('seller can see the orders details', async ({ adminPage }) => {

        /**
         * Expect result to redirect the admin dashboard URL
         */
        await expect(adminPage).toHaveURL('http://192.168.15.80/Bagisto-v222/mp-v/public/admin/dashboard')

        /**
         * Navigate to the Marketplace section
         */
        await adminPage.click('//p[contains(.," Marketplace ")]');

        /**
         * Expect that the seller Section Should be displayed.
         */
        await expect(adminPage).toHaveURL('http://192.168.15.80/Bagisto-v222/mp-v/public/admin/marketplace/sellers');

        /**
         * Click On the Order section
         */
        await adminPage.click('(//a[contains(.," Orders ") ])[2]');

        /**
         * Click on the First Eye icon using the Index
         */
        await adminPage.click('(//span[@class="cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-200 dark:hover:bg-gray-800 ltr:ml-1 rtl:mr-1 icon-view"])[1]');

        await adminPage.waitForTimeout(2000);
        await expect(adminPage.locator('//textarea[@placeholder="Write your comment"]')).toBeVisible();

    });

    test('Seller can see the Only Pending Orders details', async ({ adminPage }) => {

        /**
         * Create the Random value
         */
        const randomID = Date.now();

        /**
         * Go to the Admin Login adminPage
         */
        await adminPage.goto('http://192.168.15.80/Bagisto-v222/mp-v/public/admin/dashboard');
    
        /**
         * Navigate to the Marketplace section
         */
        await adminPage.click('//p[contains(.," Marketplace ")]');

        /**
         * Expect that the seller Section Should be displayed.
         */
        await expect(adminPage).toHaveURL('http://192.168.15.80/Bagisto-v222/mp-v/public/admin/marketplace/sellers');

        /**
         * Click On the Order section
         */
        await adminPage.click('(//a[contains(.," Orders ") ])[2]');

        /**
             * Wait for the presence of any element with the class 'label-pending'
             */
        await adminPage.waitForSelector('p.label-pending');

        /**
         * Select all elements matching the 'label-pending' class 
         */
        const pending = await adminPage.$$('p.label-pending'); // Select all elements containing 'Pending'

        /**
         * Get the first matched element
         */
        const targetElement = pending[0];

        /**
         * Evaluate the first matched element in the adminPage's context.
         * - Traverse up three parent elements.
         * - Retrieve the last child of the third parent.
         * - Return null if the element structure is insufficient.
         */
        const lastChildHandle = await targetElement.evaluateHandle(el => {
            /**
             * Start from the selected element and traverse upwards
             */
            let parent: HTMLElement | null = el as HTMLElement;
            for (let i = 0; i < 3; i++) {
                /**
                 * Ensure parentElement exists before reassigning
                 */
                if (!parent) return null; // Safety check
                parent = parent.parentElement;
            }
            /**
             * Return the last child of the third parent, or null if it does not exist
             */
            return parent?.lastElementChild ?? null; // Get the last child of the 2nd parent
        });

        await lastChildHandle.asElement()?.click(); // Click safely

        await adminPage.waitForTimeout(2000);

        /**
         * Expect result that the adminPage is successfully navigate to the admin -> order section
         */
        await expect(adminPage.locator('//textarea[@placeholder="Write your comment"]')).toBeVisible();

    });

    test('Seller can see the Only Processing Orders details', async ({ adminPage }) => {


        /**
         * Expect result to redirect the admin dashboard URL
         */
        await adminPage.goto('http://192.168.15.80/Bagisto-v222/mp-v/public/admin/dashboard')

        /**
         * Navigate to the Marketplace section
         */
        await adminPage.click('//p[contains(.," Marketplace ")]');

        /**
         * Expect that the seller Section Should be displayed.
         */
        await expect(adminPage).toHaveURL('http://192.168.15.80/Bagisto-v222/mp-v/public/admin/marketplace/sellers');

        /**
         * Click On the Order section
         */
        await adminPage.click('(//a[contains(.," Orders ") ])[2]');

        /**
         * Wait for the presence of any element with the class 'label-pending'
         */
        // await adminPage.waitForSelector('p.label-processing');

        /**
         * Select all elements matching the 'label-pending' class 
         */
        const processing = await adminPage.$$('p.label-processing'); // Select all elements containing 'Pending'

        /**
         * Get the first matched element
         */
        const targetElement = processing[0];

        /**
         * Evaluate the first matched element in the adminPage's context.
         * - Traverse up three parent elements.
         * - Retrieve the last child of the third parent.
         * - Return null if the element structure is insufficient.
         */
        const lastChildHandle = await targetElement.evaluateHandle(el => {
            /**
             * Start from the selected element and traverse upwards
             */
            let parent: HTMLElement | null = el as HTMLElement;
            for (let i = 0; i < 3; i++) {
                /**
                 * Ensure parentElement exists before reassigning
                 */
                if (!parent) return null; // Safety check
                parent = parent.parentElement;
            }
            /**
             * Return the last child of the third parent, or null if it does not exist
             */
            return parent?.lastElementChild ?? null; // Get the last child of the 2nd parent
        });

        await lastChildHandle.asElement()?.click(); // Click safely

        await adminPage.waitForTimeout(2000);

        /**
         * Expect result that the adminPage is successfully navigate to the admin -> order section
         */
        await expect(adminPage.locator('//textarea[@placeholder="Write your comment"]')).toBeVisible();

    });

    test('Seller can see the Only Canceled Orders status details', async ({ adminPage }) => {

        /**
         * Expect result to redirect the admin dashboard URL
         */
        await adminPage.goto('http://192.168.15.80/Bagisto-v222/mp-v/public/admin/dashboard')

        /**
         * Navigate to the Marketplace section
         */
        await adminPage.click('//p[contains(.," Marketplace ")]');

        /**
         * Expect that the seller Section Should be displayed.
         */
        await expect(adminPage).toHaveURL('http://192.168.15.80/Bagisto-v222/mp-v/public/admin/marketplace/sellers');

        /**
         * Click On the Order section
         */
        await adminPage.click('(//a[contains(.," Orders ") ])[2]');

        /**
             * Wait for the presence of any element with the class 'label-pending'
             */
        await adminPage.waitForSelector('p.label-canceled');

        /**
         * Select all elements matching the 'label-pending' class 
         */
        const canceled = await adminPage.$$('p.label-canceled'); // Select all elements containing 'Pending'

        /**
         * Get the first matched element
         */
        const targetElement = canceled[0];

        /**
         * Evaluate the first matched element in the adminPage's context.
         * - Traverse up three parent elements.
         * - Retrieve the last child of the third parent.
         * - Return null if the element structure is insufficient.
         */
        const lastChildHandle = await targetElement.evaluateHandle(el => {
            /**
             * Start from the selected element and traverse upwards
             */
            let parent: HTMLElement | null = el as HTMLElement;
            for (let i = 0; i < 3; i++) {
                /**
                 * Ensure parentElement exists before reassigning
                 */
                if (!parent) return null; // Safety check
                parent = parent.parentElement;
            }
            /**
             * Return the last child of the third parent, or null if it does not exist
             */
            return parent?.lastElementChild ?? null; // Get the last child of the 2nd parent
        });

        await lastChildHandle.asElement()?.click(); // Click safely

        await adminPage.waitForTimeout(2000);

        /**
         * Expect result that the adminPage is successfully navigate to the admin -> order section
         */
        await expect(adminPage.locator('//textarea[@placeholder="Write your comment"]')).toBeVisible();

    });

});