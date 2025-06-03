import { test, expect } from '../../setup';
import { sellerLogin } from "../../seller-setup";

test('Seller can cancel the order', async ({ adminPage }) => {

/**
 * You have already created the order 
 */    
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
     * Store the Value in the Variable
     */
    const seller_can_cancel_order =  await adminPage.locator('//input[@id="marketplace[settings][seller][can_cancel_order]"]').isChecked();

    /**
     * Call a Function to the Sign In button
     */
    await sellerLogin(adminPage);

    /**
     * Navigate to the Seller Order section
     */
    await adminPage.click('//a//span[contains(.," Orders")]');
    await adminPage.waitForTimeout(2000);

    /**
     * Click on the "Which View" button for the order with a status of "Pending." According to the index: [3]
     */
    await adminPage.waitForSelector('p.label-pending');
    const pending = await adminPage.$$('p.label-pending'); // Select all elements containing 'Pending'
    const targetElement = pending[0]; // First match
    const lastChildHandle = await targetElement.evaluateHandle(el => {
        let parent: HTMLElement | null = el as HTMLElement;
        for (let i = 0; i < 3; i++) {
            if (!parent) return null; // Safety check
            parent = parent.parentElement;
        }
        return parent?.lastElementChild ?? null; // Get the last child of the 3rd parent
    });
    await lastChildHandle.asElement()?.click(); // Click safely
    await adminPage.waitForTimeout(2000);

    if(seller_can_cancel_order){
        await expect(adminPage.locator('//a[contains(.,"Cancel ")]')).toBeVisible();

        await adminPage.click('//a[contains(.,"Cancel ")]');

        await adminPage.waitForSelector('div.absolute.p-5')
        await adminPage.click('//button[contains(.,"Agree")]');

        await adminPage.waitForTimeout(2000);
        await expect(adminPage.locator('div.fixed.top-5')).toHaveText('Order has been canceled');
    }else{
        await expect(adminPage.locator('//a[contains(.,"Cancel ")]')).not.toBeVisible();
    }
});