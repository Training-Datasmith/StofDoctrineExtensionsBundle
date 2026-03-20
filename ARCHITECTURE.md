# Architecture: StofDoctrineExtensionsBundle

## Purpose

Symfony bundle that integrates the `gedmo/doctrine-extensions` library (Blameable, Loggable, Sluggable, SoftDeleteable, Sortable, Timestampable, Translatable, Tree, Uploadable, etc.) with the Symfony framework's DI container, event system, and request stack.

## Directory Structure

```
src/
  Stof_Doctrine_Extensions_Bundle.php         Bundle entry point
  DependencyInjection/
    Configuration.php                          Config tree (which extensions + ORM/MongoDB/PHPCR managers)
    Stof_Doctrine_Extensions_Extension.php     Registers listeners and configuration
    Compiler/
      Reader_Pass.php                          Wires annotation/attribute reader
      Validate_Extension_Configuration_Pass.php  Validates manager config references
  EventListener/
    Blame_Listener.php                         Blameable: injects current user from Security token
    Locale_Listener.php                        Translatable: injects current locale from request
    Logger_Listener.php                        Loggable: writes log entries
  Tool/
    Locale_Synchronizer.php                    Keeps Translatable locale in sync with Symfony locale
    Request_Stack_Ip_Address_Provider.php      Provides client IP for IpTraceable extension
    Token_Storage_Actor_Provider.php           Provides current user for Blameable
  Uploadable/
    Mime_Type_Guesser_Adapter.php              Adapts Symfony mime type guesser to Gedmo's interface
    Uploadable_Manager.php                     Manages file uploads for Uploadable entities
    Uploaded_File_Info.php                     Value object wrapping an uploaded file
    Validator_Configurator.php                 Registers Uploadable validation constraints
  Resources/config/                            Service definitions per extension (PHP format)
```

## Key Design Decisions

- **Thin integration layer**: This bundle does not reimplement any Doctrine extension behaviour; it only wires `gedmo/doctrine-extensions` into Symfony by providing the required listeners, locale/user providers, and configuration.
- **Per-extension service files**: Each extension (blameable, loggable, etc.) has its own `Resources/config/*.php` service file loaded conditionally based on configuration, keeping unused extensions from registering any services.
- **Provider pattern**: Rather than reading `$_SERVER` or `TokenStorage` directly in Gedmo listeners, this bundle provides `RequestStackIpAddressProvider` and `TokenStorageActorProvider` that adapt Symfony services to Gedmo's interfaces.

## Extension Points

- Enable/disable individual extensions per Doctrine manager in `config/packages/stof_doctrine_extensions.yaml`.
- Override `blame_listener`, `locale_listener`, or `logger_listener` service definitions to customise behaviour.

## Dependency Flow

```
Doctrine event (onFlush, loadClassMetadata, etc.)
  -> Gedmo extension listener
    -> BlameListener -> TokenStorageActorProvider -> Security TokenStorage -> current user
    -> LocaleListener -> LocaleSynchronizer -> RequestStack -> current locale
    -> LoggerListener -> writes LogEntry entity
```
