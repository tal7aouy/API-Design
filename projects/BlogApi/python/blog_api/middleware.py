import logging
import uuid

from fastapi import Request

logger = logging.getLogger(__name__)


async def request_logger(request: Request, call_next):
    request_id = request.headers.get('x-request-id') or str(uuid.uuid4())
    request.state.request_id = request_id

    response = await call_next(request)
    response.headers['X-Request-Id'] = request_id

    logger.info(
        {
            'request_id': request_id,
            'method': request.method,
            'path': request.url.path,
            'status': response.status_code,
        }
    )

    return response
