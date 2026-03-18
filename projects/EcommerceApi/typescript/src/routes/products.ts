import express from 'express';
import { ProductService } from '../services/ProductService';

export function createProductsRouter(productService: ProductService): express.Router {
  const router = express.Router();

  router.get('/products', (req, res, next) => {
    try {
      const limit = Math.min(50, Number(req.query.limit ?? 20));
      const products = productService.listProducts(limit);
      res.status(200).json({
        data: products,
        pagination: {
          next_cursor: null,
          previous_cursor: null,
          limit,
        },
      });
    } catch (err) {
      next(err);
    }
  });

  router.post('/products', (req, res, next) => {
    try {
      const { name, description, price_cents, available_quantity } = req.body ?? {};
      const errors: Record<string, string[]> = {};

      if (!name) errors.name = ['This field is required.'];
      if (!description) errors.description = ['This field is required.'];
      if (typeof price_cents !== 'number' || price_cents <= 0) {
        errors.price_cents = ['Must be a positive integer.'];
      }
      if (typeof available_quantity !== 'number' || available_quantity < 0) {
        errors.available_quantity = ['Must be a non-negative integer.'];
      }

      if (Object.keys(errors).length > 0) {
        return res.status(422).json({
          error: {
            code: 'VALIDATION_ERROR',
            message: 'Validation failed.',
            details: errors,
          },
        });
      }

      const product = productService.createProduct({
        name,
        description,
        priceCents: price_cents,
        availableQuantity: available_quantity,
      });

      res.status(201).json(product);
    } catch (err) {
      next(err);
    }
  });

  return router;
}
