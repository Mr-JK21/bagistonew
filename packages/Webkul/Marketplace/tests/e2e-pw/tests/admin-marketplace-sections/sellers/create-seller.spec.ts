import { test, expect } from "../../../setup";

test('admin can create a new seller', async ({ adminPage }) => {

    const randomID = Date.now();

    const sellerDetails = {
        name: 'seller'+ randomID,
        email: 'seller' + randomID + '@example.com',
        shop_title: ' seller shop',
        url: 'test' + randomID,
        street_address: '12842 King Ramp, Volkmanhaven, Ohio - 50568, Democratic Republic of the Congo',
        phone_number: randomID,
        postcode: '25165611',
        city: 'California',
    };

    await adminPage.goto('http://192.168.15.80/Bagisto-v222/mp-v/public/admin/dashboard');
    /**
     * Expect that the seller Section Should be displayed.
     */
    await adminPage.goto('http://192.168.15.80/Bagisto-v222/mp-v/public/admin/marketplace/sellers');
    
    /**
     * Click To the Add Seller Button
     */
    await adminPage.click('//button[@class="primary-button"]');

    /**
     * Await For the Create seller Modal
     */
    await adminPage.waitForSelector('p.text-lg');

    /**
     * Fill the Create seller Form
     */
    const seller_name =  await adminPage.fill('//input[@placeholder="Name"]',sellerDetails.name);
    await adminPage.fill('//input[@placeholder="Email"]', sellerDetails.email);
    await adminPage.fill('//input[@placeholder="Shop Title"]', sellerDetails.shop_title);
    await adminPage.fill('//input[@placeholder="Shop Url"]', sellerDetails.url);
    await adminPage.fill('//input[@placeholder="Street Address"]', sellerDetails.street_address);
    await adminPage.fill('//input[@placeholder="Phone Number"]', sellerDetails.phone_number.toString());
    await adminPage.fill('//input[@placeholder="Postcode"]', sellerDetails.postcode);
    await adminPage.fill('//input[@placeholder="City"]',sellerDetails.city);
    await adminPage.selectOption('//select[@name="country"]',{value: "US"});
    await adminPage.selectOption('//select[@name="state"]', {index: 12});

    await adminPage.click('//button[contains(.,"Save")]');
    await adminPage.waitForTimeout(2000);

    /**
     * Expect result: Pop-up message should be displayed.
     */
    await expect(adminPage.getByText('Seller Created Successfully.')).toBeVisible();
});
