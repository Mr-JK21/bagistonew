import { test, expect } from "../../../setup";

test('admin assign the category', async ({ adminPage }) => {
/**
 * Categories has been created on your Project
 */

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
     * Click On the Seller category section
     */
    await adminPage.click('//a[contains(.," Seller Categories") ]');

    /**
     * Expect that the adminPage should be navigated to the seller categories section 
     */
    await expect(adminPage).toHaveURL('http://192.168.15.80/Bagisto-v222/mp-v/public/admin/marketplace/seller-categories');

    /**
     * Click on the assign category button
     */
    await adminPage.click('//a[@class="primary-button"]');

    /**
     * Wait for this locator
     */
    await adminPage.waitForSelector('//p[contains(.," Assign Category ")]');

    /**
     * Create a variable to check the category is available or not.
     */
    const category_available=  await adminPage.locator('div.flex.flex-col').isVisible();

    /**
     * Checked if the Category is available.
     */
    if(category_available){
        // console.log('Categories display');
        /**
         * Select the seller ->  First index seller of the seller list.
         */
        await adminPage.selectOption('//select[@id="marketplace_seller_id"]', {index: 1});

        /**
         * Select the First category of the Category list.
         */
        await adminPage.click('(//span[@class="icon-uncheckbox peer-checked:icon-checked cursor-pointer rounded-md text-2xl peer-checked:text-blue-600"])[1]');
        await adminPage.waitForTimeout(2000);

        /**
         * Click on the Save category button
         */
        await adminPage.click('//button[@class="primary-button"]');

        await expect(adminPage.getByText('Category assigned Successfully.')).toBeVisible();
    } else{
        await expect(adminPage.locator('div.flex.flex-col')).not.toBeVisible();
        // console.log('Categories not display');
    }
});