// BAD EXAMPLE: Do NOT copy this style.

import express from 'express';
import { Pool } from 'pg';

const router = express.Router();
const pool = new Pool({ connectionString: process.env.DATABASE_URL });

router.post('/doCreatePost', async (req, res) => {
  // No auth, no validation, raw SQL string concatenation.
  const { title, body, userId } = req.body;

  const result = await pool.query(
    `INSERT INTO posts (title, body, user_id) VALUES ('${title}', '${body}', ${userId}) RETURNING *`,
  );

  res.send(result.rows[0]); // raw row, wrong status code
});

router.get('/getAllPosts', async (_req, res) => {
  const result = await pool.query('SELECT * FROM posts');
  res.send(result.rows); // leaking internal schema
});

export default router;
