<?php

namespace App\Models\Admin;

use Shared\{Model, Rights};
use App\Interfaces\UserInterface;
use Illuminate\Database\Eloquent\Builder;
use App\Traits\{HasEmail, HasPhoto, HasPhones, HasName};

class Admin extends Model implements UserInterface
{
    use HasEmail, HasPhoto, HasPhones, HasName;

    protected $commaSeparated = ['rights'];

    public $fillable = [
        // только на время переноса
        'old_id',
        'first_name', 'last_name', 'middle_name', 'rights', 'nickname'
    ];

    public function ips()
    {
        return $this->hasMany(AdminIp::class);
    }

    public function isBanned()
    {
        // проверяем бан только для EC
        return $_SERVER['HTTP_HOST'] === config('app.host') && $this->allowed(Rights::LK2_BANNED);
    }

    public function allowed($right)
    {
        return in_array($right, $this->rights);
    }

    // public function scopeOrderByName($query)
    // {
    //     return $query->orderByRaw("
    //         IF(first_name = '', 1, 0) asc,
    //         IF(first_name = '', nickname, CONCAT(last_name, first_name, middle_name)) asc
    //     ");
    // }

    /**
     * Если данные изменились, должен перезалогиниться (решили в целях безопасности)
     */
    public function wasUpdated()
    {
        return $this->updated_at != self::whereId($this->id)->value('updated_at');
    }

    /**
	 * Можно ли логиниться с этого IP?
	 */
	public function allowedToLogin()
	{
        if (app('env') === 'local' || app('env') == 'testing') {
            return (object)[
                'confirm_by_sms' => false
            ];
        }

        $current_ip = ip2long($_SERVER['HTTP_X_REAL_IP']);
        foreach($this->ips as $ip) {
            $ip_from = ip2long(trim($ip->ip_from));
            $ip_to = ip2long(trim($ip->ip_to ?: $ip->ip_from));
            if ($current_ip >= $ip_from && $current_ip <= $ip_to) {
                return $ip;
            }
        }

        return false;
    }

    public static function boot()
    {
        parent::boot();

        static::addGlobalScope('defaultOrder', function(Builder $builder) {
            $builder
                ->orderByRaw("IF(FIND_IN_SET(" . Rights::LK2_BANNED . ", rights) > 0, 1, 0) asc")
                ->orderByName();
        });
    }
}
