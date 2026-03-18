import express from 'express';
import { PostService } from '../services/PostService';

export function createPostsRouter(postService: PostService): express.Router {
  const router = express.Router();

  router.get('/posts', (req, res, next) => {
    try {
      const limit = Math.min(50, Number(req.query.limit ?? 20));
      const posts = postService.listPosts(limit);
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

  router.post('/posts', (req, res, next) => {
    try {
      const { title, body, author_id } = req.body ?? {};

      const errors: Record<string, string[]> = {};
      if (!title) errors.title = ['This field is required.'];
      if (!body) errors.body = ['This field is required.'];
      if (!author_id) errors.author_id = ['This field is required.'];

      if (Object.keys(errors).length > 0) {
        return res.status(422).json({
          error: {
            code: 'VALIDATION_ERROR',
            message: 'Validation failed.',
            details: errors,
          },
        });
      }

      const post = postService.createPost({
        title,
        body,
        authorId: author_id,
      });

      res.status(201).json(post);
    } catch (err) {
      next(err);
    }
  });

  return router;
}
