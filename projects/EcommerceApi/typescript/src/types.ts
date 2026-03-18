export interface ProductBase {
  name: string;
  description: string;
  priceCents: number;
  availableQuantity: number;
}

export interface ProductCreate extends ProductBase {}

export interface Product extends ProductBase {
  id: string;
}

export interface OrderItem {
  productId: string;
  quantity: number;
}

export interface OrderCreate {
  items: OrderItem[];
}

export interface Order {
  id: string;
  userId: string;
  items: OrderItem[];
  createdAt: string;
}
