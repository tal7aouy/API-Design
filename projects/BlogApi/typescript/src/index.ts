import express from 'express';
import bodyParser from 'body-parser';
import { createPostsRouter } from './routes/posts';
import { PostService } from './services/PostService';
import { errorHandler } from './middleware/errorHandler';
import { requestLogger } from './middleware/requestLogger';

const app = express();
const port = process.env.PORT ? Number(process.env.PORT) : 3001;

app.use(bodyParser.json());
app.use(requestLogger);

const postService = new PostService();
app.use('/api/v1', createPostsRouter(postService));

app.use(errorHandler);

app.listen(port, () => {
  console.log(`Blog API (TypeScript) listening on http://localhost:${port}`);
});
