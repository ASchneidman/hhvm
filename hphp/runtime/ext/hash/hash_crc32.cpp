/*
   +----------------------------------------------------------------------+
   | HipHop for PHP                                                       |
   +----------------------------------------------------------------------+
   | Copyright (c) 2010-present Facebook, Inc. (http://www.facebook.com)  |
   | Copyright (c) 1997-2010 The PHP Group                                |
   +----------------------------------------------------------------------+
   | This source file is subject to version 3.01 of the PHP license,      |
   | that is bundled with this package in the file LICENSE, and is        |
   | available through the world-wide-web at the following url:           |
   | http://www.php.net/license/3_01.txt                                  |
   | If you did not receive a copy of the PHP license and are unable to   |
   | obtain it through the world-wide-web, please send a note to          |
   | license@php.net so we can mail you a copy immediately.               |
   +----------------------------------------------------------------------+
*/

#include "hphp/runtime/ext/hash/hash_crc32.h"
#include "hphp/runtime/ext/hash/php_hash_crc32_tables.h"

namespace HPHP {
///////////////////////////////////////////////////////////////////////////////

struct PhpCrc32Ctx {
  unsigned int state;
};

hash_crc32::hash_crc32(Crc32Variant variant)
  : HashEngine(4, 4, sizeof(PhpCrc32Ctx)), m_variant(variant) {
}

void hash_crc32::hash_init(void *context_) {
  PhpCrc32Ctx *context = (PhpCrc32Ctx*)context_;
  context->state = ~0;
}

void hash_crc32::hash_update(void *context_, const unsigned char *input,
                             unsigned int len) {
  PhpCrc32Ctx *context = (PhpCrc32Ctx*)context_;
  size_t i;
  switch (m_variant) {
    case Crc32Variant::Crc32B:
    for (i = 0; i < len; ++i) {
      context->state = (context->state >> 8) ^
        crc32b_table[(context->state ^ input[i]) & 0xff];
    }
    break;
    case Crc32Variant::Crc32:
    for (i = 0; i < len; ++i) {
      context->state = (context->state << 8) ^
        crc32_table[(context->state >> 24) ^ (input[i] & 0xff)];
    }
    break;
    case Crc32Variant::Crc32C:
    for (i = 0; i < len; ++i) {
      context->state = (context->state >> 8) ^
        crc32c_table[(context->state ^ input[i]) & 0xff];
    }
    break;
  }
}

void hash_crc32::hash_final(unsigned char *digest, void *context_) {
  PhpCrc32Ctx *context = (PhpCrc32Ctx*)context_;
  context->state=~context->state;

  if (m_variant == Crc32Variant::Crc32C
#if defined(HPHP_OSS)
    // This was a bug in PHP, see PHP bug #45028
    // We currently rely on the old behaviour
    || m_variant == Crc32Variant::Crc32B
#endif
  ) {
    digest[0] = (unsigned char) ((context->state >> 24) & 0xff);
    digest[1] = (unsigned char) ((context->state >> 16) & 0xff);
    digest[2] = (unsigned char) ((context->state >> 8) & 0xff);
    digest[3] = (unsigned char) (context->state & 0xff);
  } else {
    digest[3] = (unsigned char) ((context->state >> 24) & 0xff);
    digest[2] = (unsigned char) ((context->state >> 16) & 0xff);
    digest[1] = (unsigned char) ((context->state >> 8) & 0xff);
    digest[0] = (unsigned char) (context->state & 0xff);
  }

  context->state = 0;
}

///////////////////////////////////////////////////////////////////////////////
}
