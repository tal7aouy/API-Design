import { Product, ProductCreate } from '../types';
import { v4 as uuid } from 'uuid';

export class ProductService {
  private readonly products = new Map<string, Product>();

  listProducts(limit: number): Product[] {
    return Array.from(this.products.values()).slice(0, Math.min(limit, 50));
  }

  getProduct(productId: string): Product | undefined {
    return this.products.get(productId);
  }

  createProduct(payload: ProductCreate): Product {
    const product: Product = {
      id: uuid(),
      ...payload,
    };
    this.products.set(product.id, product);
    return product;
  }

  adjustInventory(productId: string, delta: number): void {
    const product = this.products.get(productId);
    if (!product) return;
    product.availableQuantity = Math.max(0, product.availableQuantity + delta);
  }
}
