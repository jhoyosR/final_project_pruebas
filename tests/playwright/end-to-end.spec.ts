import { test, expect } from '@playwright/test';

test.describe('Flujo completo de categorías y productos', () => {

    test('Crear categoría, ver categoría, crear producto, ver producto, eliminar producto y eliminar categoria', async ({ page }) => {
        
        // Crear categoría
        await page.goto('/view/categories');

        await page.getByRole('button', { name: 'Nueva Categoría' }).click();

        await page.locator('#catName').fill('Bebidas');

        await page.getByRole('button', { name: 'Guardar' }).click();

        // Espera a que recargue la tabla
        await page.waitForTimeout(800);

        const categoryCell = page.locator('#categoriesTable tr td:first-child', { hasText: 'Bebidas' });
        await expect(categoryCell).toBeVisible();


        // Crear Producto
        await page.goto('/view/products');

        await page.getByRole('button', { name: 'Nuevo Producto' }).click();

        // Seleccionar categoría recién creada
        await page.locator('#prodCategory').selectOption({ label: 'Bebidas' });

        await page.locator('#prodName').fill('Coca Cola');
        await page.locator('#prodPrice').fill('5500');
        await page.locator('#prodStock').fill('25');
        await page.locator('#prodDesc').fill('Gaseosa de 400ml');

        await page.getByRole('button', { name: 'Guardar' }).click();

        // Esperar que se actualice la tabla
        await page.waitForTimeout(1000);

        // Verificar que el producto aparece
        const productRow = page.locator('#productsTable tr', { hasText: 'Coca Cola' });
        await expect(productRow).toBeVisible();

        const priceCell = productRow.locator('td', { hasText: '$5500' });
        await expect(priceCell).toBeVisible();

        // Eliminar un producto
        const row = page.locator('#productsTable tr', { hasText: 'Coca Cola' });
        await expect(row).toBeVisible();

        // Captura el alert y lo acepta
        page.once('dialog', dialog => {
            dialog.accept(); 
        });

        // Clic en Eliminar
        await row.getByRole('button', { name: 'Eliminar' }).click();

        // Esperar tabla actualizada
        await page.waitForTimeout(1200);

        // Validar que desapareció
        await expect(
            page.locator('#productsTable tr', { hasText: 'Coca Cola' })
        ).toHaveCount(0);

        // Eliminar categoria
        await page.goto('/view/categories');

        const categoryRow = page.locator('#categoriesTable tr', { hasText: 'Bebidas' });
        await expect(categoryRow).toBeVisible();

        // Captura el alert y lo acepta
        page.once('dialog', dialog => {
            dialog.accept(); 
        });

        // Clic en eliminar
        await categoryRow.getByRole('button', { name: 'Eliminar' }).click();

        await page.waitForTimeout(1200);

        await expect(
            page.locator('#categoriesTable tr', { hasText: 'Bebidas' })
        ).toHaveCount(0);
    });

});

