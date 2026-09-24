## dmlaravel

​       dmlaravel是用于连接dm数据库的的Laravel的一个包，它是Laravel中illuminate/Database的扩展，用于操作dm数据库并进行一系列的交互。

## Change Logs

#### dmlaravel v12.0.0（2026-09-24）

- **仅支持 Laravel 12 / PHP 8.2+**（不再兼容 Laravel 6 与 Laravel 11）—— L6/L11 项目请继续使用 `11.x` 分支的版本
- **约束收紧**：`php: ^8.2`、`illuminate/database|pagination: ^12.0`（原为 `php: ^7.3|^8.0` 与 `^6.0|^11.0|^12.0`）
- `DmConnection`：`getDefaultQueryGrammar()` / `getDefaultSchemaGrammar()` 使用 L12 的 `Grammar::__construct(Connection)` 签名（L11 起 `withTablePrefix()` 已移除）
- `Query\DmBuilder::toRawSql()`：直接调用 `substituteBindingsIntoRawSql()`（L11 起 `Grammar::setConnection()` 已移除）
- `Schema\DmBuilder::createBlueprint()`：直接使用 `Blueprint::__construct(Connection, $table, $callback)` 三参数签名（不再运行时反射判断，也无需 `setTablePrefix()`）
- `Schema\DmBuilder`：不再声明 `$defaultTimePrecision`（L12 父类自带 `public static ?int $defaultTimePrecision = 0` 与配套静态方法）
- `Schema\DmBuilder`：`getTables($schema = null)` / `getViews($schema = null)` / `parseSchemaAndTable($reference, $withDefaultSchema = null)` 对齐 L12 原生签名
- `Schema\Grammars\DmGrammar`：`wrapTable($table, $prefix = null)` 对齐 L12 原生签名；`compileColumns()` / `compileTables()` 末位参数可选并自动补查 ID（L12 调用时只传 schema/table）；`compileRenameColumn()` / `compileChange()` 对齐 L12 原生两参数签名
- `composer.json`：修正 `branch-alias`（原 `dev-master` 与实际分支名不符 → `12.x-dev`）；`require-dev` 升为 `mockery/mockery: ^1.6`、`phpunit/phpunit: ^11.0`
- 实测环境：真实达梦库 DM8 + Laravel 12.69 + PHP 8.2（连接 / `SELECT` / 分页 `paginate` / Schema：`getTables` 49 张表、`getColumns`、`getIndexes`、`getForeignKeys`、`getViews` / 建表、插入、加列、重命名列）

#### dmlaravel v11.0.3（2025-03-03）

- 变更了项目到DamengDB，修改了composer.json中的name
- 调整了包名为dmlaravel

#### dmlaravel v11.0.2（2025-01-09）

- 修复了migration日期时间字段默认值的问题
- 修复了migration数值数据类型精度问题
- 修复了migration字符数据类型长度超过边界值不报错的问题
#### dmlaravel v11.0.1（2024-12-09）
- 修复了DB_PORT非法时不报错的问题
- 修复了表中内容的中文显示乱码的问题
#### dmlaravel v11.0.0（2024-11-09）
- 创建适配Laravel11的分支
