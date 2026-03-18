import express from 'express';
import { PostService } from './services/PostService';

export function createPostsRouter(postService: PostService): express.Router {
  const router = express.Router();

  router.get('/posts', async (req, res, next) => {
    try {
      const limit = parseInt(req.query.limit as string, 10) || 20;
      const cursor = (req.query.cursor as string) || undefined;

      const posts = await postService.listPosts(limit, cursor);

      res.status(200).json({
        data: posts,
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

  router.post('/posts', async (req, res, next) => {
    try {
      const { title, body, author_id } = req.body ?? {};

      if (!title || !body || !author_id) {
        return res.status(422).json({
          error: {
            code: 'VALIDATION_ERROR',
            message: 'Missing required fields.',
            details: {
              title: title ? [] : ['This field is required.'],
              body: body ? [] : ['This field is required.'],
              author_id: author_id ? [] : ['This field is required.'],
            },
          },
        });
      }

      const post = await postService.createPost({
        title,
        body,
        authorId: author_id,
      });

      res.status(201).json(post);
    } catch (err) {
      if (err instanceof Error) {
        return res.status(422).json({
          error: {
            code: 'VALIDATION_ERROR',
            message: err.message,
          },
        });
      }
      next(err);
    }
  });

  return router;
}
