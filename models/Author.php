<?php
namespace Models;

final class Author extends Model
{
    protected const TABLE = 'authors';
    protected const JSON_COLUMNS = ['social_links'];
}
