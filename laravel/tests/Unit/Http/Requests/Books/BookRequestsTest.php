<?php

namespace Tests\Unit\Http\Requests\Books;

use App\Http\Requests\Books\StoreBookRequest;
use App\Http\Requests\Books\UpdateBookRequest;
use App\Models\Book;
use App\Models\BookUserData;
use App\Rules\BookRatingRule;
use Illuminate\Validation\Rules\Unique;
use PHPUnit\Framework\TestCase;

/**
 * Прямые проверки FormRequest домена Books: authorize(), структура
 * rules() и messages() у Store/Update. Сами правила (exists/unique,
 * integer:strict, BookRatingRule) валидируются на живом реквесте
 * энд-ту-энд в Feature\Api\BookApiTest.
 *
 * Кворк в messages(): кастомные ключи вида 'binding_type.id.exists'
 * недостижимы — правило навешено на атрибут 'binding_type' (без .id),
 * поэтому реальный ключ 'binding_type.exists' не покрыт кастомным
 * сообщением и отдаёт дефолт. Достижимы только title/original_title/
 * isbn.unique и authors.*.id.
 */
class BookRequestsTest extends TestCase
{
    private function storeRules(): array
    {
        return (new StoreBookRequest)->rules();
    }

    private function updateRules(): array
    {
        return (new UpdateBookRequest)->rules();
    }

    public function test_authorize_is_always_true(): void
    {
        $this->assertTrue((new StoreBookRequest)->authorize());
        $this->assertTrue((new UpdateBookRequest)->authorize());
    }

    public function test_store_rules_cover_all_params(): void
    {
        $this->assertSame([
            Book::PARAM_TITLE,
            Book::PARAM_ORIGINAL_TITLE,
            Book::PARAM_ISBN,
            Book::PARAM_PAGES,
            Book::PARAM_CIRCULATION,
            Book::PARAM_SIZE,
            Book::PARAM_PUBLISH_YEAR,
            Book::PARAM_LIVELIB_ID,
            Book::PARAM_GOODREADS_ID,
            Book::PARAM_FANTLAB_ID,
            Book::PARAM_LIVELIB_RATING,
            Book::PARAM_GOODREADS_RATING,
            Book::PARAM_AUTHORS,
            Book::PARAM_AUTHORS.'.*.id',
            Book::PARAM_BINDING_TYPE,
            Book::PARAM_PAPER_TYPE,
            Book::PARAM_PUBLISHING_HOUSE,
            Book::PARAM_PUBLISHING_BRAND,
            Book::PARAM_BOOK_SERIES,
            Book::PARAM_BOOK_USER_DATA,
            Book::PARAM_BOOK_USER_DATA.'.'.BookUserData::PARAM_RELEASE_DATE,
            Book::PARAM_BOOK_USER_DATA.'.'.BookUserData::PARAM_LISTEN_PRICE_VALUE,
            Book::PARAM_BOOK_USER_DATA.'.'.BookUserData::PARAM_COMMENT,
        ], array_keys($this->storeRules()));
    }

    public function test_update_rules_cover_all_params(): void
    {
        $this->assertSame(
            array_keys($this->storeRules()),
            array_keys($this->updateRules()),
        );
    }

    public function test_title_rules_are_required_string_with_unique(): void
    {
        foreach ([$this->storeRules(), $this->updateRules()] as $rules) {
            $titleRules = $rules[Book::PARAM_TITLE];

            $this->assertContains('required', $titleRules);
            $this->assertContains('string', $titleRules);
            $this->assertContains('min:2', $titleRules);
            $this->assertContains('max:255', $titleRules);
            $this->assertCount(
                1,
                array_filter($titleRules, fn ($rule) => $rule instanceof Unique),
            );
        }
    }

    public function test_publish_year_is_required_strict_four_digits(): void
    {
        foreach ([$this->storeRules(), $this->updateRules()] as $rules) {
            $yearRules = $rules[Book::PARAM_PUBLISH_YEAR];

            $this->assertContains('required', $yearRules);
            $this->assertContains('integer:strict', $yearRules);
            $this->assertContains('min_digits:4', $yearRules);
            $this->assertContains('max_digits:4', $yearRules);
        }
    }

    public function test_pages_and_circulation_are_strict_integers(): void
    {
        foreach ([$this->storeRules(), $this->updateRules()] as $rules) {
            $this->assertContains('integer:strict', $rules[Book::PARAM_PAGES]);
            $this->assertContains('integer:strict', $rules[Book::PARAM_CIRCULATION]);
        }
    }

    public function test_ratings_use_book_rating_rule(): void
    {
        foreach ([$this->storeRules(), $this->updateRules()] as $rules) {
            foreach ([Book::PARAM_LIVELIB_RATING, Book::PARAM_GOODREADS_RATING] as $key) {
                $this->assertCount(
                    1,
                    array_filter($rules[$key], fn ($rule) => $rule instanceof BookRatingRule),
                );
            }
        }
    }

    public function test_book_user_data_rules(): void
    {
        $releaseKey = Book::PARAM_BOOK_USER_DATA.'.'.BookUserData::PARAM_RELEASE_DATE;
        $priceKey = Book::PARAM_BOOK_USER_DATA.'.'.BookUserData::PARAM_LISTEN_PRICE_VALUE;
        $commentKey = Book::PARAM_BOOK_USER_DATA.'.'.BookUserData::PARAM_COMMENT;

        foreach ([$this->storeRules(), $this->updateRules()] as $rules) {
            $this->assertContains('sometimes', $rules[Book::PARAM_BOOK_USER_DATA]);
            // формат из правил; сам он падает в BookUserDataDTO → 500
            // (см. BookApiTest::test_store_with_release_date_in_rule_format_is_500)
            $this->assertContains('date_format:Y.m.d', $rules[$releaseKey]);
            $this->assertContains('integer', $rules[$priceKey]);
            $this->assertContains('max:300', $rules[$commentKey]);
        }
    }

    public function test_authors_rule_requires_array_with_existing_ids(): void
    {
        foreach ([$this->storeRules(), $this->updateRules()] as $rules) {
            $this->assertContains('required', $rules[Book::PARAM_AUTHORS]);
            $this->assertContains('array', $rules[Book::PARAM_AUTHORS]);
            $this->assertContains('required', $rules[Book::PARAM_AUTHORS.'.*.id']);
        }
    }

    public function test_messages_cover_ten_keys(): void
    {
        $messages = (new StoreBookRequest)->messages();

        $this->assertSame([
            Book::PARAM_TITLE.'.unique',
            Book::PARAM_ORIGINAL_TITLE.'.unique',
            Book::PARAM_ISBN.'.unique',
            Book::PARAM_BINDING_TYPE.'.id.unique',
            Book::PARAM_BINDING_TYPE.'.id.exists',
            Book::PARAM_PAPER_TYPE.'.id.exists',
            Book::PARAM_AUTHORS.'.*.id',
            Book::PARAM_PUBLISHING_HOUSE.'.id.exists',
            Book::PARAM_PUBLISHING_BRAND.'.id.exists',
            Book::PARAM_BOOK_SERIES.'.id.exists',
        ], array_keys($messages));

        $this->assertSame(
            'A Book with this title already exists',
            $messages[Book::PARAM_TITLE.'.unique'],
        );
        $this->assertSame(
            'Author {:attribute} is not found',
            $messages[Book::PARAM_AUTHORS.'.*.id'],
        );
    }

    public function test_update_messages_inherited_from_abstract(): void
    {
        $this->assertSame(
            (new StoreBookRequest)->messages(),
            (new UpdateBookRequest)->messages(),
        );
    }
}
