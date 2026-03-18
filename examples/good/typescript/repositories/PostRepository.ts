import { PostDto } from "../dto/PostDto"

export class PostRepository {
  private readonly posts = new Map<string, PostDto>()
  private seq = 0

  private nextId(): string {
    this.seq += 1
    return `post_${this.seq}`
  }

  async list(limit: number, _cursor?: string): Promise<PostDto[]> {
    return Array.from(this.posts.values()).slice(0, Math.min(limit, 50))
  }

  async create(
    title: string,
    body: string,
    authorId: string,
  ): Promise<PostDto> {
    const post: PostDto = {
      id: this.nextId(),
      title,
      body,
      author_id: authorId,
      created_at: new Date().toISOString(),
    }
    this.posts.set(post.id, post)
    return post
  }
}
