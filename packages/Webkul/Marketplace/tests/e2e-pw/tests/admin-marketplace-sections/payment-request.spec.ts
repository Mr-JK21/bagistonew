import { test, expect } from "../../setup";

test('Payment Request', async ({ adminPage }) => {
               
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
    await adminPage.click('//a[contains(.," Payment Requests ")]');
    await adminPage.waitForTimeout(2000);

    const record =  await adminPage.getByText('No Records Available.').isVisible();

    if(record){
        console.log('No Records available');
    } else{
        console.log('Record Availble');

        await adminPage.click('(//button[contains(.," Pay Now ") ])[1]');
        await adminPage.waitForSelector('div.flex.justify-between.px-4');

        await adminPage.fill('//textarea[@placeholder="Comment"]','thank you so musch');
        await adminPage.click('//button[contains(@class,"primary-button")]');

        await expect(adminPage.getByText('Seller is Paid successfully!')).toBeVisible();
    }
});