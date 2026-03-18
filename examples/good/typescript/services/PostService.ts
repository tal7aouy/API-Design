import { PostRepository } from '../repositories/PostRepository';
import { PostDto } from '../dto/PostDto';

export class PostService {
  constructor(private readonly posts: PostRepository) {}

  async listPosts(limit: number, cursor?: string): Promise<PostDto[]> {
    const safeLimit = Math.max(1, Math.min(limit || 20, 50));
    return this.posts.list(safeLimit, cursor);
  }

  async createPost(input: { title: string; body: string; authorId: string }): Promise<PostDto> {
    const { title, body, authorId } = input;

    if (!title || title.length < 3) {
      throw new Error('Title must be at least 3 characters.');
    }
    if (!body || body.length < 10) {
      throw new Error('Body must be at least 10 characters.');
    }

    return this.posts.create(title, body, authorId);
  }
}
