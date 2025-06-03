import { test, expect, Page } from '@playwright/test';
import { fillInTinymce } from "../../utils/tinymce";
import { sellerLogin } from "../../seller-setup";

test('Seller Profile Complete/Update', async ({ page }) => {

    const randomID = Date.now();
    /**
     * Call seller login Function
     */
    await sellerLogin(page);

    const sellerDetails = {
        shop_title: "Electronics Shop",
        meta_title: "Meta Title",
        meta_keywords: "Meta Keywords",
        meta_description: "Meta Description",
        phone_number: randomID,
        street_address: "California",
        postcode: "90009",
        city: "Caala",
    };

    /**
     * To check the Profile score section is visible
     */
    await expect(page.locator('//h2[contains(.," Profile Score ")]')).toBeVisible();

    /**
     * Navigate to Manage Profile
     */
    await page.click('//span[contains(.," Manage Profile ")]');

    await page.waitForTimeout(2000); /* Wait for login to process */

    /**
     * Fill Profile Details.
     */
    await page.fill('//input[@name="shop_title"]', sellerDetails.shop_title);
    await page.fill('//input[@name="phone"]', sellerDetails.phone_number.toString());

    /**
     * Fill the "About Store" inside iframe
     */
    await fillInTinymce(page, '#content_ifr', 'hgfasghdfhgsdf'); // Filling the TinyMCE editor

    /**
     * Fill the "Meta Information"
     */
    await page.fill('//input[@name="meta_title"]', sellerDetails.meta_title);
    await page.fill('//input[@name="meta_keywords"]', sellerDetails.meta_keywords);
    await page.fill('//textarea[@name="meta_description"]', sellerDetails.meta_description);

    /**
     * Fill Privacy Policy, Shipping Policy, Return Policy inside iframe
     */
    await fillInTinymce(page, '#privacy_policy_ifr', 'hghasdgfh');
    await fillInTinymce(page, '#shipping_policy_ifr', 'hghasdgfh');
    await fillInTinymce(page, '#return_policy_ifr', 'hghasdgfh');

    /**
     * Fill Address Information
     */
    await page.fill('//input[@placeholder="Street Address"]', sellerDetails.street_address);
    await page.fill('//input[@placeholder="Postcode"]', sellerDetails.postcode);
    await page.fill('//input[@placeholder="City"]', sellerDetails.city);

    /**
     * Select Country and State
     */
    await page.selectOption('//select[@name="country"]', 'US');
    await page.selectOption('//select[@placeholder="State"]', 'CA');

    /**
     * Click on Save Profile button
     */
    await page.click('//button[contains(.," Save Profile" )]');
    
    /**
     * Check the Profile is updated successfully.
     */

    await expect(page.locator('(//div[contains(@class, "flex w-max")])[1]')).toHaveText('Your Profile is updated successfully');    
});