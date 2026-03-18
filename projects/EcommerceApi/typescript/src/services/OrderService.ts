import { OrderCreate, Order } from '../types';
import { ProductService } from './ProductService';
import { v4 as uuid } from 'uuid';

export class OrderService {
  private readonly orders = new Map<string, Order>();
  private readonly idempotency = new Map<string, Order>();

  constructor(private readonly productService: ProductService) {}

  listOrders(userId: string): Order[] {
    return Array.from(this.orders.values()).filter((o) => o.userId === userId);
  }

  createOrder(userId: string, payload: OrderCreate, idempotencyKey: string): Order {
    if (!idempotencyKey) {
      throw new Error('Idempotency key is required.');
    }

    const existing = this.idempotency.get(idempotencyKey);
    if (existing) {
      return existing;
    }

    // Validate
    payload.items.forEach((item) => {
      const product = this.productService.getProduct(item.productId);
      if (!product) {
        throw new Error(`Product ${item.productId} not found`);
      }
      if (product.availableQuantity < item.quantity) {
        throw new Error(`Insufficient inventory for ${product.name}`);
      }
    });

    // Deduct inventory
    payload.items.forEach((item) => {
      this.productService.adjustInventory(item.productId, -item.quantity);
    });

    const order: Order = {
      id: uuid(),
      userId,
      items: payload.items,
      createdAt: new Date().toISOString(),
    };

    this.orders.set(order.id, order);
    this.idempotency.set(idempotencyKey, order);

    return order;
  }
}
