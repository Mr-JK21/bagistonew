import { test, expect } from '../../setup';
import { sellerLogin } from "../../seller-setup";
import { fillInTinymce } from "../../utils/tinymce";
import { customerLogin } from '../../customer-setup';

test('create the seller simple products and checkout', async ({ adminPage }) => {

  const randomID = Date.now();

    const productDetails = {
        sku: 'SKU'+ randomID,
        product_name: 'Product Name'+ randomID,
        short_description: ' Fashion Product',
        description: ' Fashion Product',
        meta_title: 'Meta Title',
        meta_keywords: 'Meta Keyword',
        meta_description: 'Meta Description',
        price: '100',
        weight: '0.322',
        inventories: '50',
    };

    const addressCredential = {
      companyName: 'Company name',
      firstName: 'First Name',
      lastName: 'Last Name',
      mailId: 'customer@example.com',
    };

    await sellerLogin(adminPage);   

    await adminPage.click('//span[contains(.," Products ")]');

    /**
     * Click to the Add New Product button
     */
    await adminPage.click('//a[contains(.,"Add New Product")]');

    /**
     * Check that after redirect to the seller product create page
     */
    await expect(adminPage).toHaveURL('http://192.168.15.80/Bagisto-v222/mp-v/public/seller/products/create');

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
    await adminPage.fill('//input[@name="sku"]',productDetails.sku );

    /**
     * Click to Continue button
     */
    await adminPage.click('//button[contains(.,"Continue")]');

    await adminPage.waitForTimeout(5000);

    /**
     * Fill the Product Name
     */
    const product_name = await adminPage.fill('//input[@name="name"]', productDetails.product_name);

    /**
     * Fill the Description and Short DEscription using the Function
     */
    await fillInTinymce(adminPage, '#short_description_ifr', productDetails.short_description);
    await fillInTinymce(adminPage, '#description_ifr', productDetails.description);

    /**
     * Fill the Meta Title details
     */
    await adminPage.fill('//textarea[@name="meta_title"]', productDetails.meta_title);
    await adminPage.fill('//textarea[@name="meta_keywords"]', productDetails.meta_keywords);
    await adminPage.fill('//textarea[@name="meta_description"]', productDetails.meta_description);

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
    await adminPage.fill('//input[@name="inventories[1]"]' , productDetails.inventories)

    /**
     * Click on the save button
     */
    await adminPage.click('//button[contains(.,"Save Product") ]');

    /**
     * Checked the Expected pop-up message "Product updated successfully"
     */
    await expect(adminPage.locator('div.fixed.top-5')).toHaveText('Product updated successfully');


    await adminPage.goto('http://192.168.15.80/Bagisto-v222/mp-v/public/admin/marketplace/products');
    
    /**
     * Select the first checkbox using the Index
     */
    await adminPage.click("(//label[@class='icon-uncheckbox peer-checked:icon-checked cursor-pointer rounded-md text-2xl peer-checked:text-blue-600'])[1]");
    
    /**
     * Click on Select Action dropdown
     */
    await adminPage.click("//span[contains(.,'Select Action')]");
    // await page.waitForTimeout(2000);
        
    /**
     * Hover over Update Status option and display the dropdown options
     */
    const updateStatus = await adminPage.locator("xpath=//span[contains(.,'Update Status')]");
    await updateStatus.hover();
    await adminPage.waitForTimeout(2000); 

    /**
     * Select the Approval button
     */
    await adminPage.click("(//a[@class='whitespace-no-wrap block rounded-t px-4 py-2 text-sm text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-950'])[1]");
    
    /**
     * Click on the Agree button
     */
    await adminPage.click("//button[@class='primary-button']");   
    await adminPage.waitForTimeout(2000);
    await expect(adminPage.locator('text= Product Updated successfully.')).toBeVisible();   

    await customerLogin(adminPage);

    /**
     * Search the Created Product
     */
    await adminPage.fill('(//input[@placeholder="Search products here"])[1]',productDetails.product_name);
    await adminPage.press('body', 'Enter');

    await adminPage.waitForTimeout(2000);

    /**
     * Click the First product of the Search Result
     */
    await adminPage.locator('//div[@class="mt-8 grid grid-cols-3 gap-8 max-1060:grid-cols-2 max-md:mt-5 max-md:justify-items-center max-md:gap-x-4 max-md:gap-y-5"][1]/div[1]').click();

    /**
     * Add the product to the cart 
     */
    await adminPage.locator("//button[@type='submit' and contains(@class, 'secondary-button')]").click();
        
    /**
     * Open the shopping cart 
     */
    await adminPage.locator("(//span[@role='button' and contains(@class, 'icon-cart') and @tabindex='0'])[1]").click();
    
    /**
     * Continue to checkout 
     */
    await adminPage.locator("(//a[@href='http://192.168.15.80/Bagisto-v222/mp-v/public/checkout/onepage' and contains(@class, 'bg-navyBlue')])[1]").click();
    
    /**
     * Fill in the shipping address details
     */
    await adminPage.getByRole('textbox', { name: 'Company Name' }).fill('webkul');
    await adminPage.getByRole('textbox', { name: 'First Name' }).fill('webkul');
    await adminPage.getByRole('textbox', { name: 'Last Name' }).fill('webkul');
    await adminPage.getByRole('textbox', { name: 'email@example.com' }).fill('webkul@example.com');
    await adminPage.getByRole('textbox', { name: 'Street Address' }).fill('noida');
    await adminPage.locator('select[name="billing\\.country"]').selectOption('IN'); // Country: India
    await adminPage.locator('select[name="billing\\.state"]').selectOption('UP');   // State: UP
    await adminPage.getByRole('textbox', { name: 'City' }).fill('noida');
    await adminPage.getByRole('textbox', { name: 'Zip/Postcode' }).fill('251664');
    await adminPage.getByRole('textbox', { name: 'Telephone' }).fill('154895584');
    
    /**
     * click on save button
     */
    await adminPage.getByRole('button', { name: 'Save' }).click();
  
    /**
     * Proceed with the checkout
     */
    await adminPage.getByRole('button', { name: 'Proceed' }).click();
    
    /**
     * Select the shipping method 
     */ 
    await adminPage.locator('div:nth-child(2) > .icon-radio-unselect').click();
    
    /**
     * Choose the payment method
     */
    await adminPage.click('//img[@title="Cash On Delivery"]');

    /**
     * Place the order
     */
    await adminPage.getByRole('button', { name: 'Place Order' }).click();

    await adminPage.waitForSelector('//p[@class="text-2xl font-medium max-md:text-base"]');  

    /**
     * Checked that After completed the Order display the locator
     */
    await expect(adminPage.locator('//p[@class="text-2xl font-medium max-md:text-base"]')).toBeVisible();
});