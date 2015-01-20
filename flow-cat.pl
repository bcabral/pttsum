#!/usr/bin/perl

  use Net::Patricia;
  my $pt = new Net::Patricia;

# Origin prefixes by ASNs

print STDERR "Loading prefix file into memory... ";
open (fil, "originas.txt");
@db = <fil>;
close(fil);
print STDERR "done.\n";

print STDERR "Creating data structure... ";
foreach $rrow(@db) {
    my ($rAS,$rnet, $rmask) = split(/\s+/, $rrow);
    $pt->add_string("$rnet/$rmask", $rAS);
}
print STDERR "done.\n";

print STDERR "Processing data... ";
while (<STDIN>) {
  @row=split(",");

  $row[22]=$pt->match_string($row[10]);
  $row[23]=$pt->match_string($row[11]);
    print join(",", @row) . "\n"
}
print STDERR "done.\n";

