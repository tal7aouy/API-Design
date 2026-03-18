import { Post, PostCreate } from '../types';
import { v4 as uuid } from 'uuid';

export class PostService {
  private readonly posts = new Map<string, Post>();

  listPosts(limit: number): Post[] {
    return Array.from(this.posts.values()).slice(0, Math.min(limit, 50));
  }

  createPost(payload: PostCreate): Post {
    const post: Post = {
      id: uuid(),
      title: payload.title,
      body: payload.body,
      authorId: payload.authorId,
      createdAt: new Date().toISOString(),
    };

    this.posts.set(post.id, post);
    return post;
  }
}
