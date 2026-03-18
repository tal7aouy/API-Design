import express from 'express';
import { OrderService } from '../services/OrderService';

export function createOrdersRouter(orderService: OrderService): express.Router {
  const router = express.Router();

  // NOTE: This is a minimal auth placeholder for demo purposes.
  // In a real app use proper JWT middleware and user context.
  function getUserId(req: express.Request): string {
    const header = req.headers.authorization as string | undefined;
    if (!header?.startsWith('Bearer ')) {
      throw new Error('Missing bearer token');
    }
    const token = header.slice('Bearer '.length);
    // In a real app, verify token and extract user id.
    return token || 'anon';
  }

  router.get('/orders', (req, res, next) => {
    try {
      const userId = getUserId(req);
      const orders = orderService.listOrders(userId);
      res.status(200).json(orders);
    } catch (err) {
      next(err);
    }
  });

  router.post('/orders', (req, res, next) => {
    try {
      const userId = getUserId(req);
      const idempotencyKey = req.header('Idempotency-Key');
      const { items } = req.body ?? {};

      if (!Array.isArray(items) || items.length === 0) {
        return res.status(422).json({
          error: {
            code: 'VALIDATION_ERROR',
            message: 'Request must include at least one order item.',
          },
        });
      }

      const order = orderService.createOrder(userId, { items }, idempotencyKey ?? '');
      res.status(201).json(order);
    } catch (err) {
      next(err);
    }
  });

  return router;
}
