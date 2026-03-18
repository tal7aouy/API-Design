import express from 'express';
import bodyParser from 'body-parser';
import { createProductsRouter } from './routes/products';
import { createOrdersRouter } from './routes/orders';
import { ProductService } from './services/ProductService';
import { OrderService } from './services/OrderService';
import { errorHandler } from './middleware/errorHandler';
import { requestLogger } from './middleware/requestLogger';

const app = express();
const port = process.env.PORT ? Number(process.env.PORT) : 3002;

app.use(bodyParser.json());
app.use(requestLogger);

const productService = new ProductService();
const orderService = new OrderService(productService);

app.use('/api/v1', createProductsRouter(productService));
app.use('/api/v1', createOrdersRouter(orderService));

app.use(errorHandler);

app.listen(port, () => {
  console.log(`E-commerce API (TypeScript) listening on http://localhost:${port}`);
});
