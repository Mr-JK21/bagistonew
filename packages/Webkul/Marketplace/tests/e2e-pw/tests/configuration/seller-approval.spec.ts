import { test, expect } from "../../setup";
import { sellerLogin } from "../../seller-setup";
import { fillInTinymce } from "../../utils/tinymce";
import * as fs from 'fs';
       
test('seller login/register admin/approval', async ({ adminPage }) => {

    const randomID = Date.now();

    const sellerCredentials = {
        name: "seller" + randomID,
        url: "test"+ randomID,
        email: "seller" + randomID + "@example.com",
        password: "admin123",
        confirm_password: "admin123",
    };

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
     *Click to the Seller Open Store button adminPage.
     */
    await adminPage.click('//a[@class="primary-button flex items-center gap-2.5"]');

    /**
     *Check the Customer is redirected to Seller Login/Register adminPage.
     */
    await expect(adminPage).toHaveURL('http://192.168.15.80/Bagisto-v222/mp-v/public/seller/register');

    /**
     * Fill Seller registration form
     */
    await adminPage.fill('//input[@placeholder="Name"]', sellerCredentials.email);
    await adminPage.fill('//input[@placeholder="Shop Url"]', sellerCredentials.url);
    await adminPage.fill('//input[@placeholder="email@example.com"]', sellerCredentials.email);
    await adminPage.fill('//input[@placeholder="Password"]', sellerCredentials.password);
    await adminPage.fill('//input[@placeholder="Confirm Password"]', sellerCredentials.confirm_password);

    await adminPage.click('//button[contains(.," Register ")]');

    /**
     *Check the customer/seller is redirected to the seller login adminPage.
     */
    await expect(adminPage).toHaveURL('http://192.168.15.80/Bagisto-v222/mp-v/public/seller/login');

    /**
     * Save the seller credential to the "credential.json" file
     */
    fs.writeFileSync('credentials.json', JSON.stringify(sellerCredentials));

    /**
     * Call the Function using the seller-setup helper
     */
    await sellerLogin(adminPage);
    
    /**
     *Check the Without approval seller should not logged in.
     */
    await expect(adminPage.locator('//div[@class="fixed top-5 z-[1001] grid justify-items-end gap-2.5 max-sm:hidden ltr:right-5 rtl:left-5"]')).toHaveText('Your activation seeks admin approval');

    /**
     *For approval of the seller, Admin should login.
     */

    await adminPage.goto('http://192.168.15.80/Bagisto-v222/mp-v/public/admin/');

    /**
     *Verify the Admin is redirected to the Dashboard.
     */
    await expect(adminPage.locator('//input[@placeholder="Mega Search"]')).toBeVisible();

    /**
     *Navigate to the Marketplace section
     */
    await adminPage.click('//p[ contains(.,"Marketplace")]');

    /**
     *Navigate to the Marketplace -> Seller section
     */
    await adminPage.click('(//a[@href="http://192.168.15.80/Bagisto-v222/mp-v/public/admin/marketplace/sellers"])[2]');

    /**
     *Select the first checkbox in the Seller list
     */
    await adminPage.click('(//label[@class="icon-uncheckbox peer-checked:icon-checked cursor-pointer rounded-md text-2xl peer-checked:text-blue-600"])[1]')

    /**
     *Click on the Select Action dropdown
     */
    await adminPage.click('//button[contains(., "Select Action") ]')

    /**
     *Hover over the Update Status option
     */
    const updateStatus = await adminPage.locator("xpath=//span[contains(.,'Update Status')]");
    await updateStatus.hover();

    /**
     *Click on the second dropdown option
     */
    await adminPage.click("//a[contains(.,'Approved')]");

    /**
     *Click on the Agree button
     */
    await adminPage.click('//button[contains(.,"Agree")]');
    
    /**
     *See the Message Seller updated successfully!
     */
    await expect(adminPage.locator('text=Seller updated successfully!')).toBeVisible();

    /**
     * Call the Function using the seller-setup helper
     */
     await sellerLogin(adminPage);

    /**
     *Check the Seller is redirected to the Seller Dashboard.
     */
    await expect(adminPage).toHaveURL('http://192.168.15.80/Bagisto-v222/mp-v/public/seller/dashboard');  

    /**
     * To check the Profile score section is visible
     */
    await expect(adminPage.locator('//h2[contains(.," Profile Score ")]')).toBeVisible();

    /**
     * Navigate to Manage Profile
     */
    await adminPage.click('//span[contains(.," Manage Profile ")]');

    await adminPage.waitForTimeout(2000); /* Wait for login to process */

    /**
     * Fill Profile Details.
     */
    await adminPage.fill('//input[@name="shop_title"]', sellerDetails.shop_title);
    await adminPage.fill('//input[@name="phone"]', sellerDetails.phone_number.toString());

    /**
     * Fill the "About Store" inside iframe
     */
    await fillInTinymce(adminPage, '#content_ifr', 'hgfasghdfhgsdf'); // Filling the TinyMCE editor

    /**
     * Fill the "Meta Information"
     */
    await adminPage.fill('//input[@name="meta_title"]', sellerDetails.meta_title);
    await adminPage.fill('//input[@name="meta_keywords"]', sellerDetails.meta_keywords);
    await adminPage.fill('//textarea[@name="meta_description"]', sellerDetails.meta_description);

    /**
     * Fill Privacy Policy, Shipping Policy, Return Policy inside iframe
     */
    await fillInTinymce(adminPage, '#privacy_policy_ifr', 'hghasdgfh');
    await fillInTinymce(adminPage, '#shipping_policy_ifr', 'hghasdgfh');
    await fillInTinymce(adminPage, '#return_policy_ifr', 'hghasdgfh');

    /**
     * Fill Address Information
     */
    await adminPage.fill('//input[@placeholder="Street Address"]', sellerDetails.street_address);
    await adminPage.fill('//input[@placeholder="Postcode"]', sellerDetails.postcode);
    await adminPage.fill('//input[@placeholder="City"]', sellerDetails.city);

    /**
     * Select Country and State
     */
    await adminPage.selectOption('//select[@name="country"]', 'US');
    await adminPage.selectOption('//select[@placeholder="State"]', 'CA');

    /**
     * Click on Save Profile button
     */
    await adminPage.click('//button[contains(.," Save Profile" )]');
    
    await adminPage.waitForTimeout(2000);
    /**
     * Check the Profile is updated successfully.
     */
        
    await expect(adminPage.getByRole('paragraph').filter({ hasText: 'Your Profile is updated' })).toBeVisible();

});