import { test, expect } from "../../../setup";

test('admin assign the products to the Sellers', async ({ adminPage }) => {
           
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
     * Search the seller using search box which has completed the Profile and approved the seller by the admin
     */
    await adminPage.fill('//input[@name="search"]', 'seller1741695932871@example.com');
    await adminPage.getByRole('textbox', { name: 'Search', exact: true }).press('Enter');
    
/**
 * Ensure that the seller Profile should be Completed. 
 */    
    /**
     * Click on the Assign button
     */
    await adminPage.click('(//a[contains(.," Assign ")])[1]');

    await adminPage.waitForSelector('p.text-xl');

    /**
     * Only search the admin product that have already ceated in the Database.
     * Enter 3 terms to suggest the products that match the keyword
     */
    await adminPage.fill('//input[@placeholder="Search Products"]','gloves');

    /**
     * Alwayes click on the First product using the Index
     */
    await adminPage.click('(//div[@class="grid max-h-[400px] overflow-y-auto border-b border-slate-300 last:border-b-0"])[1]');

    /**
     * Wait
     */
    await adminPage.waitForSelector('p.text-xl');

    /**
     * Assign the Product
     */
    await adminPage.selectOption('//select[@name="condition"]', {value :"new"});
    await adminPage.fill('//input[@name="price"]','50');
    await adminPage.fill('//input[@placeholder="Default"]','20');
    await adminPage.fill('//textarea[@placeholder="Description"]','fdsgfsdhgdfhghdfgh');
    await adminPage.click('//button[contains(.," Save ")]');

    /**
     * This Message should be displayed.
     */
    await expect(adminPage.getByText('Product assigned successfully to the seller.')).toBeVisible();
});